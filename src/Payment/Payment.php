<?php

declare(strict_types=1);

namespace App\Payment;

use App\Common\DomainEventPublisher;
use Ramsey\Uuid\UuidInterface;

class Payment
{
    use DomainEventPublisher;

    private UuidInterface $id;

    private UuidInterface $orderId;

    private int $amount;

    private Status $status;

    public function __construct(UuidInterface $id, UuidInterface $orderId, int $amount)
    {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->amount = $amount;
        $this->status = Status::created();
    }

    public function setPaid(): void
    {
        $this->status = Status::paid();
        $this->publish(new Paid($this->id, $this->orderId));
    }
}