<?php

declare(strict_types=1);

namespace Brammm\TestingWorkshop\Http;

use Brammm\TestingWorkshop\Discount\Calculator;
use Brammm\TestingWorkshop\Provider\OrderProvider;
use Money\MoneyFormatter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Ramsey\Uuid\Uuid;

final class GetOrderDiscount
{
    public function __construct(
        private readonly OrderProvider $orderProvider,
        private readonly Calculator $calculator,
        private readonly MoneyFormatter $formatter,
    ) {
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $order = $this->orderProvider->findById(Uuid::fromString($args['id']));

        $data = [
            'total' => $this->formatter->format($order->total()),
            'discount' => $this->formatter->format($this->calculator->calculateDiscount($order)),
        ];

        $response->getBody()->write(json_encode($data));

        return $response->withAddedHeader('Content-type', 'application/json');
    }
}
