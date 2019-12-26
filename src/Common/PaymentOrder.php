<?php

declare(strict_types=1);

namespace App\Common;

use LitGroup\Enumerable\Enumerable;

class PaymentOrder extends Enumerable
{
    public static function online(): self
    {
        return self::createEnum('online');
    }

    public static function afterDelivery(): self
    {
        return self::createEnum('afterDelivery');
    }
}