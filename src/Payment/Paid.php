<?php

declare(strict_types=1);

namespace App\Payment;

use App\Common\DomainEvent;
use Ramsey\Uuid\UuidInterface;

class Paid extends DomainEvent
{
    private UuidInterface $paymentId;

    private UuidInterface $orderId;

    public function __construct(UuidInterface $paymentId, UuidInterface $orderId)
    {
        parent::__construct();
        $this->paymentId = $paymentId;
        $this->orderId = $orderId;
    }

    public function getPaymentId(): UuidInterface
    {
        return $this->paymentId;
    }

    public function getOrderId(): UuidInterface
    {
        return $this->orderId;
    }
}