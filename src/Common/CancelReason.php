<?php

declare(strict_types=1);

namespace App\Common;

use LitGroup\Enumerable\Enumerable;

class CancelReason extends Enumerable
{
    public static function notPaid(): self
    {
        return self::createEnum('notPaid');
    }

    public static function insufficientProducts(): self
    {
        return self::createEnum('insufficientProducts');
    }
}