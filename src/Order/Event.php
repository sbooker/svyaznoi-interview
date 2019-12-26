<?php

declare(strict_types=1);

namespace App\Order;

use App\Common\DomainEvent;
use Ramsey\Uuid\UuidInterface;

abstract class Event extends DomainEvent
{
    private UuidInterface $orderId;

    public function __construct(UuidInterface $orderId)
    {
        parent::__construct();
        $this->orderId = $orderId;
    }

    public function getOrderId(): UuidInterface
    {
        return $this->orderId;
    }
}