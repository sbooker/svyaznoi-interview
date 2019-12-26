<?php

declare(strict_types=1);

namespace App\Bucket;

use App\Common\PaymentOrder;
use Ramsey\Uuid\UuidInterface;

class OrderedBucket
{
    private UuidInterface $id;

    private Bucket $bucket;

    private PaymentOrder $paymentOrder;

    public function __construct(UuidInterface $id, Bucket $bucket, PaymentOrder $paymentOrder)
    {
        $this->id = $id;
        $this->bucket = $bucket;
        $this->paymentOrder = $paymentOrder;
    }
}