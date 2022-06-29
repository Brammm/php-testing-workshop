<?php

declare(strict_types=1);

use Brammm\TestingWorkshop\Discount\Calculator;
use Brammm\TestingWorkshop\Discount\MoreThanFiftyDiscount;
use Brammm\TestingWorkshop\Provider\CustomerProvider;
use Brammm\TestingWorkshop\Provider\DatabaseCustomerProvider;
use Brammm\TestingWorkshop\Provider\DatabaseOrderProvider;
use Brammm\TestingWorkshop\Provider\OrderProvider;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use Money\MoneyFormatter;

return [
    Connection::class => fn () => DriverManager::getConnection([
        'dbname' => 'testing-workshop',
        'user' => 'root',
        'password' => 'root',
        'host' => 'php-testing-workshop-mysql',
        'driver' => 'pdo_mysql',
    ]),

    Calculator::class => fn () => new Calculator(
        new MoreThanFiftyDiscount(),
    ),

    CustomerProvider::class => fn (Connection $conn) => new DatabaseCustomerProvider($conn),
    OrderProvider::class => fn (Connection $conn) => new DatabaseOrderProvider($conn),

    MoneyFormatter::class => fn () => new IntlMoneyFormatter(
        new NumberFormatter('nl_BE', NumberFormatter::CURRENCY),
        new ISOCurrencies()
    ),
];
