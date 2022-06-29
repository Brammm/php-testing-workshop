<?php

declare(strict_types=1);

namespace Brammm\TestingWorkshop\Discount;

use Brammm\TestingWorkshop\Model\Order;
use Money\Money;

interface Discount
{
    public function calculate(Order $order): Money;
}
