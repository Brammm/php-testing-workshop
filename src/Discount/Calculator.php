<?php

declare(strict_types=1);

namespace Brammm\TestingWorkshop\Discount;

use Brammm\TestingWorkshop\Model\Order;
use Money\Money;

class Calculator
{
    /** @var Discount[] */
    private array $discounts;

    public function __construct(Discount ...$discounts)
    {
        $this->discounts = $discounts;
    }

    public function calculateDiscount(Order $order): Money
    {
        foreach ($this->discounts as $discount) {
            $discount = $discount->calculate($order);

            if ($discount->isPositive()) {
                return $discount;
            }
        }

        return Money::EUR(0);
    }
}
