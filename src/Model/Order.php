<?php

declare(strict_types=1);

namespace Brammm\TestingWorkshop\Model;

use DateTimeImmutable;
use Money\Money;
use Ramsey\Uuid\UuidInterface;

class Order
{
    /**
     * @param OrderLine[] $lines
     */
    public function __construct(
        public UuidInterface $id,
        public UuidInterface $customerId,
        public array $lines,
        public ?DateTimeImmutable $refundedAt = null,
    ) {
    }

    public function total(): Money
    {
        return array_reduce(
            $this->lines,
            fn (Money $total, OrderLine $line) => $total->add($line->price->multiply($line->amount)),
            Money::EUR(0)
        );
    }
}
