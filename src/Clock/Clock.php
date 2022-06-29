<?php

declare(strict_types=1);

namespace Brammm\TestingWorkshop\Clock;

use DateTimeImmutable;

interface Clock
{
    public function now(): DateTimeImmutable;
}
