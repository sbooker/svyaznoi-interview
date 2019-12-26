<?php

declare(strict_types=1);

namespace App\Payment;

use LitGroup\Enumerable\Enumerable;

class Status extends Enumerable
{
    public static function created(): self
    {
        return self::createEnum('created');
    }

    public static function paid(): self
    {
        return self::createEnum('paid');
    }
}