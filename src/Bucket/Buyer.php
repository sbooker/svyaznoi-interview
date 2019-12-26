<?php

declare(strict_types=1);

namespace App\Bucket;

use App\Common\DomainEventPublisher;
use App\Common\PaymentOrder;
use App\Domain\Canceled;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Buyer
{
    use DomainEventPublisher;

    private UuidInterface $id;

    private Bucket $bucket;

    /** @var array<UuidInterface => Bucket> */
    private array $ordered = [];

    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
        $this->newBucket();
    }

    public function addToBucket(UuidInterface $productId, int $price, int $quantity): void
    {
        $this->bucket->add($productId, $price, $quantity);
    }

    public function removeFromBucket(UuidInterface $productId): void
    {
        $this->bucket->remove($productId);
    }

    public function setProductQuantity(UuidInterface $productId, int $quantity): void
    {
        $this->bucket->setItemQuantity($productId, $quantity);
    }

    public function newBucket(): void
    {
        $this->bucket = new Bucket();
    }

    public function order(PaymentOrder $paymentOrder): void
    {
        $orderedId = Uuid::uuid4();
        $ordered = new OrderedBucket($orderedId, $this->bucket, $paymentOrder);
        $this->ordered[$orderedId->toString()] = $ordered;
        $this->newBucket();
        $this->publish(new Ordered($this->id, $orderedId));
    }

    public function getOrderedBucket(UuidInterface $orderedBucketId): ?OrderedBucket
    {
        if (isset($this->ordered[$orderedBucketId->toString()])) {
            return $this->ordered[$orderedBucketId->toString()];
        }

        return null;
    }
}