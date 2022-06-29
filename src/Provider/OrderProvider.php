<?php

declare(strict_types=1);

namespace Brammm\TestingWorkshop\Provider;

use Brammm\TestingWorkshop\Model\Order;
use Ramsey\Uuid\UuidInterface;

interface OrderProvider
{
    public function findById(UuidInterface $uuid): Order;

    /**
     * @return Order[]
     */
    public function findAll(): array;
}
