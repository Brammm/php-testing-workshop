<?php

declare(strict_types=1);

namespace Brammm\TestingWorkshop\Provider;

use Brammm\TestingWorkshop\Model\Order;
use Brammm\TestingWorkshop\Model\OrderLine;
use Doctrine\DBAL\Connection;
use Exception;
use Money\Money;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class DatabaseOrderProvider implements OrderProvider
{
    public function __construct(
        private readonly Connection $connection
    ) {
    }

    public function findById(UuidInterface $uuid): Order
    {
        $data = $this->connection->fetchAllAssociative(
            'SELECT * FROM `order` o INNER JOIN order_line ol ON ol.order_id = o.id WHERE o.id = ?',
            [$uuid->toString()]
        );

        if (!$data) {
            throw new Exception('Not Found');
        }

        $order = [
            'id' => $data[0]['id'],
            'customer_id'=> $data[0]['customer_id'],
            'refunded_at' => $data[0]['refunded_at'],
        ];

        $order['lines'] = $this->collectLines($order['id'], $data);

        $lines = array_map(static fn (array $row) => new OrderLine(
            $row['description'],
            $row['amount'],
            Money::EUR($row['price'])
        ), $order['lines']);

        return new Order(
            Uuid::fromString($order['id']),
            Uuid::fromString($order['customer_id']),
            $lines,
            $order['refunded_at'] ? new \DateTimeImmutable($order['refunded_at']) : null,
        );
    }

    /**
     * @return Order[]
     */
    public function findAll(): array
    {
        $data = $this->connection->fetchAllAssociative(
            'SELECT * FROM `order` o INNER JOIN order_line ol ON ol.order_id = o.id'
        );

        $orders = array_values(array_reduce(
            $data,
            function (array $orders, array $row) use ($data) {
                $orders[$row['id']] = [
                    'id' => $row['id'],
                    'customer_id'=> $row['customer_id'],
                    'refunded_at' => $row['refunded_at'],
                ];

                return $orders;
            },
            []
        ));

        foreach ($orders as $i => $order) {
            $orders[$i]['lines'] = $this->collectLines($order['id'], $data);
        }

        $objects = [];
        foreach ($orders as $order) {
            $lines = array_map(static fn (array $row) => new OrderLine(
                $row['description'],
                $row['amount'],
                Money::EUR($row['price'])
            ), $order['lines']);

            $objects[] = new Order(
                Uuid::fromString($order['id']),
                Uuid::fromString($order['customer_id']),
                $lines,
                $order['refunded_at'] ? new \DateTimeImmutable($order['refunded_at']) : null,
            );
        }

        return $objects;
    }

    private function collectLines(string $orderId, array $data)
    {
        return array_map(static fn (array $row) => [
            'description' => $row['description'],
            'amount' => $row['amount'],
            'price' => $row['price'],
        ], array_filter($data, static fn (array $row) => $row['id'] === $orderId));
    }
}
