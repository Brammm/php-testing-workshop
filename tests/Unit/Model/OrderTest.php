<?php

declare(strict_types=1);

namespace Brammm\TestingWorkshop\Unit\Model;

use Brammm\TestingWorkshop\Model\Order;
use Brammm\TestingWorkshop\Model\OrderLine;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class OrderTest extends TestCase
{
    public function testItCalculatesTotal(): void
    {
        $order = new Order(
            Uuid::uuid4(),
            Uuid::uuid4(),
            [
                new OrderLine('Some product', 1, Money::EUR(1000)),
                new OrderLine('Some other product', 2, Money::EUR(2000)),
            ]
        );

        self::assertEquals(Money::EUR(5000), $order->total());
    }
}
