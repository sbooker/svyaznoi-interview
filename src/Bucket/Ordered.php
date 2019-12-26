<?php

declare(strict_types=1);

namespace App\Bucket;

use App\Common\DomainEvent;
use Ramsey\Uuid\UuidInterface;

class Ordered extends DomainEvent
{
    private UuidInterface $buyerId;

    private UuidInterface $bucketId;

    public function __construct(UuidInterface $buyerId, UuidInterface $bucketId)
    {
        parent::__construct();
        $this->buyerId = $buyerId;
        $this->bucketId = $bucketId;
    }

    public function getBuyerId(): UuidInterface
    {
        return $this->buyerId;
    }

    public function getBucketId(): UuidInterface
    {
        return $this->bucketId;
    }
}