<?php

declare(strict_types=1);

namespace Brammm\TestingWorkshop\Http;

use Brammm\TestingWorkshop\Provider\CustomerProvider;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class GetCustomers
{
    public function __construct(
        private readonly CustomerProvider $customerProvider
    ) {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $customers = $this->customerProvider->findAll();

        $data = array_map(fn ($customer) => [
            'id' => $customer->id->toString(),
            'name' => $customer->name,
        ], $customers);

        $response->getBody()->write(json_encode($data));

        return $response->withAddedHeader('Content-type', 'application/json');
    }
}
