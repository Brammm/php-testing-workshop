<?php

declare(strict_types=1);

use Brammm\TestingWorkshop\Discount\Calculator;
use Brammm\TestingWorkshop\Discount\MoreThanFiftyDiscount;
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

    Calculator::class => fn() => new Calculator(
        new MoreThanFiftyDiscount(),
    ),

    MoneyFormatter::class => fn () => new IntlMoneyFormatter(
        new NumberFormatter('nl_BE', NumberFormatter::CURRENCY),
        new ISOCurrencies()
    ),
];
