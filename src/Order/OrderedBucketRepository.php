<?php


namespace App\Order;


use Ramsey\Uuid\UuidInterface;

interface OrderedBucketRepository
{
    public function get(UuidInterface $buyerId, UuidInterface $bucketId): Bucket;
}