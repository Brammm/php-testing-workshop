<?php

declare(strict_types=1);

namespace Brammm\TestingWorkshop\Discount;

use Brammm\TestingWorkshop\Clock\Clock;
use Brammm\TestingWorkshop\Model\Order;
use Brammm\TestingWorkshop\Provider\CustomerProvider;
use DateInterval;
use Money\Money;

final class LoyalCustomerDiscount implements Discount
{
    public function __construct(
        private readonly CustomerProvider $customerProvider,
        private readonly Clock $clock,
    )
    {
    }

    public function calculate(Order $order): Money
    {
        $customer = $this->customerProvider->findById($order->customerId);

        $twoYearsAgo = $this->clock->now()->sub(new DateInterval('P2Y'));

        if ($customer->customerSince > $twoYearsAgo) {
            return Money::EUR(0);
        }

        return $order->total()->multiply('0.2');
    }
}
