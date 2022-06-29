<?php

declare(strict_types=1);

namespace Brammm\TestingWorkshop\Unit\Discount;

use Brammm\TestingWorkshop\Discount\Calculator;
use Brammm\TestingWorkshop\Model\Order;
use Brammm\TestingWorkshop\Model\OrderLine;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class CalculatorTest extends TestCase
{
    public function testItReturnsNoDiscount(): void
    {
        $calculator = new Calculator();

        $discount = $calculator->calculateDiscount(new Order(
            Uuid::uuid4(),
            Uuid::uuid4(),
            [
                new OrderLine('Some product', 1, Money::EUR(5000))
            ],
            null
        ));

        self::assertEquals(Money::EUR(0), $discount);
    }

    public function testItReturnsDiscountForMoreThanFiftyItems(): void
    {
        $calculator = new Calculator();

        $discount = $calculator->calculateDiscount(new Order(
            Uuid::uuid4(),
            Uuid::uuid4(),
            [
                new OrderLine('Some product', 51, Money::EUR(50))
            ],
            null
        ));

        self::assertEquals(Money::EUR(255), $discount);
    }
}
