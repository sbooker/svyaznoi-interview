<?php

declare(strict_types=1);

namespace App\Order;

use LitGroup\Enumerable\Enumerable;

class Status extends Enumerable
{
    public static function created(): self
    {
        return self::createEnum('created');
    }

    public static function checked(): self
    {
        return self::createEnum('checked');
    }

    public static function delivered(): self
    {
        return self::createEnum('delivered');
    }

    public static function completed(): self
    {
        return self::createEnum('completed');
    }

    public static function canceled(): self
    {
        return self::createEnum('canceled');
    }
}