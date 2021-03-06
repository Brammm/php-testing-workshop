<?php

declare(strict_types=1);

namespace Brammm\TestingWorkshop\Discount;

use Brammm\TestingWorkshop\Model\Order;
use Money\Money;

class Calculator
{
    public function calculateDiscount(Order $order): Money
    {
        foreach ($order->lines as $line) {
            if ($line->amount > 50) {
                $lineTotal = $line->price->multiply($line->amount);

                return $lineTotal->multiply('0.1');
            }
        }

        return Money::EUR(0);
    }
}
