<?php

declare(strict_types=1);

namespace Brammm\TestingWorkshop\Provider;

use Brammm\TestingWorkshop\Model\Customer;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class CustomerProvider
{
    public function __construct(
        private readonly Connection $connection
    ) {
    }

    public function findById(UuidInterface $uuid): Customer
    {
        return $this->hydrate(
            $this->connection->fetchAllAssociative('SELECT * FROM customer WHERE id = ?', [$uuid->toString()])
        );
    }

    /**
     * @return Customer[]
     */
    public function findAll(): array
    {
        return array_map(
            [$this, 'hydrate'],
            $this->connection->fetchAllAssociative('SELECT * FROM customer ORDER BY name')
        );
    }

    /**
     * @param array{id: string, name: string, customer_since: string} $row
     */
    private function hydrate(array $row): Customer
    {
        return new Customer(
            Uuid::fromString($row['id']),
            $row['name'],
            new DateTimeImmutable($row['customer_since'])
        );
    }
}
