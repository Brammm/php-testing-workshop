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
    /**
     * @dataProvider providesTestData
     */
    public function testItReturnsNoDiscount(array $orderLines, Money $expectedDiscount): void
    {
        $calculator = new Calculator();

        $actualDiscount = $calculator->calculateDiscount(new Order(
            Uuid::uuid4(),
            Uuid::uuid4(),
            $orderLines,
            null
        ));

        self::assertEquals($expectedDiscount, $actualDiscount);
    }

    /**
     * @return array<array{OrderLine[], Money}>
     */
    public function providesTestData(): array
    {
        return [
            [
                [
                    new OrderLine('Some product', 50, Money::EUR(5000))
                ],
                Money::EUR(0)
            ],
            [
                [
                    new OrderLine('Some product', 51, Money::EUR(50))
                ],
                Money::EUR(255)
            ],
            [
                [
                    new OrderLine('Some product', 1, Money::EUR(5000)),
                    new OrderLine('Some product', 51, Money::EUR(50)),
                ],
                Money::EUR(255)
            ],
            [
                [
                    new OrderLine('Some product', 81, Money::EUR(50)),
                    new OrderLine('Some product', 51, Money::EUR(50)),
                ],
                Money::EUR(405)
            ],
        ];
    }
}
