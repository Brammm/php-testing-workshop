<?php

declare(strict_types=1);

namespace Brammm\TestingWorkshop\Unit\Discount;

use Brammm\TestingWorkshop\Discount\LoyalCustomerDiscount;
use Brammm\TestingWorkshop\Model\Customer;
use Brammm\TestingWorkshop\Model\Order;
use Brammm\TestingWorkshop\Model\OrderLine;
use Brammm\TestingWorkshop\Provider\CustomerProvider;
use Brammm\TestingWorkshop\TestClock;
use DateTimeImmutable;
use Money\Money;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class LoyalCustomerDiscountTest extends TestCase
{
    private CustomerProvider|MockObject $customerProvider;

    protected function setUp(): void
    {
        $this->customerProvider = $this->createMock(CustomerProvider::class);
    }

    public function testItReturnsNothingForNewCustomer(): void
    {
        $this->customerProvider->expects($this->once())
            ->method('findById')
            ->willReturn(new Customer(
                Uuid::uuid4(),
                'John Doe',
                new DateTimeImmutable('2021-01-01')
            ));

        $discountCalculator = new LoyalCustomerDiscount(
            $this->customerProvider,
            new TestClock(new DateTimeImmutable('2022-06-29'))
        );

        $discount = $discountCalculator->calculate(new Order(
            Uuid::uuid4(),
            Uuid::uuid4(),
            [
                new OrderLine('Some product', 1, Money::EUR(10000))
            ]
        ));

        self::assertEquals(Money::EUR(0), $discount);
    }

    public function testItReturnsDiscountForLoyalCustomer(): void
    {
        $this->customerProvider->expects($this->once())
            ->method('findById')
            ->willReturn(new Customer(
                Uuid::uuid4(),
                'John Doe',
                new DateTimeImmutable('2020-01-01')
            ));

        $discountCalculator = new LoyalCustomerDiscount(
            $this->customerProvider,
            new TestClock(new DateTimeImmutable('2022-06-29'))
        );

        $discount = $discountCalculator->calculate(new Order(
            Uuid::uuid4(),
            Uuid::uuid4(),
            [
                new OrderLine('Some product', 1, Money::EUR(10000))
            ]
        ));

        self::assertEquals(Money::EUR(2000), $discount);
    }
}
