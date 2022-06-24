<?php

declare(strict_types=1);

namespace Brammm\TestingWorkshop\Model;

use Money\Money;
use Ramsey\Uuid\UuidInterface;

class Product
{
    public UuidInterface $id;
    public string $name;
    public Money $price;
}
