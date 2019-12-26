<?php

declare(strict_types=1);

namespace App\Common;

abstract class DomainEvent
{
    private\DateTimeImmutable $occurredAt;

    public function __construct()
    {
        $this->occurredAt = new \DateTimeImmutable();
    }
}