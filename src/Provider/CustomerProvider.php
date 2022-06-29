<?php

declare(strict_types=1);

namespace Brammm\TestingWorkshop\Provider;

use Brammm\TestingWorkshop\Model\Customer;
use Ramsey\Uuid\UuidInterface;

interface CustomerProvider
{
    public function findById(UuidInterface $id): Customer;

    /**
     * @return Customer[]
     */
    public function findAll(): array;
}
