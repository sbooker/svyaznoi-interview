<?php

declare(strict_types=1);

namespace Notification;

use App\Common\CancelReason;
use Ramsey\Uuid\UuidInterface;

class Order
{
    private UuidInterface $id;

    private UuidInterface $buyerId;

    private CancelReason $cancelReason;

    public function __construct(UuidInterface $id, UuidInterface $buyerId, CancelReason $cancelReason)
    {
        $this->id = $id;
        $this->buyerId = $buyerId;
        $this->cancelReason = $cancelReason;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getBuyerId(): UuidInterface
    {
        return $this->buyerId;
    }

    public function getCancelReason(): CancelReason
    {
        return $this->cancelReason;
    }
}