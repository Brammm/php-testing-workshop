<?php

declare(strict_types=1);

namespace Brammm\TestingWorkshop\Model;

use DateTimeImmutable;
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
}
