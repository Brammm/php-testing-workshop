<?php

declare(strict_types=1);

namespace Brammm\TestingWorkshop\Http;

use Brammm\TestingWorkshop\Provider\OrderProvider;
use Money\MoneyFormatter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class GetOrders
{
    public function __construct(
        private readonly OrderProvider $orderProvider,
        private readonly MoneyFormatter $formatter,
    ) {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $orders = $this->orderProvider->findAll();

        $data = array_map(fn ($order) => [
            'id' => $order->id->toString(),
            'customer_id' => $order->customerId->toString(),
            'refunded_at' => $order->refundedAt?->format('Y-m-d H:i:s'),
            'lines' => array_map(fn ($line) => [
                'description' => $line->description,
                'amount' => $line->amount,
                'price' => $this->formatter->format($line->price),
            ], $order->lines)
        ], $orders);

        $response->getBody()->write(json_encode($data));

        return $response->withAddedHeader('Content-type', 'application/json');
    }
}
