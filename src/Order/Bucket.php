<?php

declare(strict_types=1);

namespace App\Order;

use App\Common\PaymentOrder;
use Ramsey\Uuid\UuidInterface;

class Bucket
{
    private UuidInterface $buyerId;

    private UuidInterface $bucketId;

    private PaymentOrder $paymentOrder;

    /** @var Item[] */
    private array $items;

    private int $totalPrice;

    public function __construct(UuidInterface $buyerId, UuidInterface $bucketId, PaymentOrder $paymentOrder, array $items, int $totalPrice)
    {
        $this->buyerId = $buyerId;
        $this->bucketId = $bucketId;
        $this->paymentOrder = $paymentOrder;
        $this->items = $items;
        $this->totalPrice = $totalPrice;
    }

    public function getBuyerId(): UuidInterface
    {
        return $this->buyerId;
    }

    public function getBucketId(): UuidInterface
    {
        return $this->bucketId;
    }

    public function getPaymentOrder(): PaymentOrder
    {
        return $this->paymentOrder;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotalPrice(): int
    {
        return $this->totalPrice;
    }
}