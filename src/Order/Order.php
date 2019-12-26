<?php

declare(strict_types=1);

namespace App\Order;

use App\Common\CancelReason;
use App\Common\DomainEventPublisher;
use App\Common\PaymentOrder;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Order
{
    use DomainEventPublisher;

    private UuidInterface $id;

    private UuidInterface $buyerId;

    /** @var Item[] */
    private array $items;

    private int $totalPrice;

    private PaymentOrder $paymentOrder;

    private Workflow $workflow;

    private bool $isPaid = false;

    private ?CancelReason $cancelReason = null;

    private function __construct(UuidInterface $id, UuidInterface $buyerId, array $items, int $totalPrice, PaymentOrder $paymentOrder)
    {
        if (empty($items)) {
            throw new \InvalidArgumentException();
        }
        if ($totalPrice <= 0) {
            throw new \InvalidArgumentException();
        }
        $this->id = $id;
        $this->buyerId = $buyerId;
        $this->items = $items;
        $this->totalPrice = $totalPrice;
        $this->paymentOrder = $paymentOrder;
        $this->workflow = new Workflow();

        $this->publish(new Created($this->id));
    }

    /*
     * on Bucket\Ordered
     */
    public static function create(UuidInterface $buyerId, UuidInterface $bucketId, OrderedBucketRepository $bucketRepository): self
    {
        $bucket = $bucketRepository->get($buyerId, $bucketId);

        return new self(
            Uuid::uuid4(),
            $bucket->getBuyerId(),
            $bucket->getItems(),
            $bucket->getTotalPrice(),
            $bucket->getPaymentOrder(),
        );
    }

    /*
     * on Order\Created
     */
    public function checkExistence(Warehouse $warehouse): void
    {
        if (!$this->isInStatus(Status::created())) {
            return;
        }
        foreach ($this->items as $item) {
            $item->setWarehouseQuantity($warehouse->getProductQuantity($item->getProductId()));
        }
        foreach ($this->items as $item) {
            if (!$item->existsInWarehouse()) {

                $this->markCanceled(CancelReason::insufficientProducts());
            }
        }

        $this->markChecked();
    }

    /*
     * Run periodically
     */
    public function checkOnlinePaid(int $timeout): void
    {
        if ($this->isPaid) {
            return;
        }
        if (!$this->isInStatus(Status::checked())) {
            return;
        }
        if (!$this->paymentOrder->equals(PaymentOrder::online())) {
            return;
        }
        if ($timeout <= 0) {
            throw new \InvalidArgumentException();
        }

        if ($this->getStatusChangedAt() < new \DateTimeImmutable("-{$timeout}seconds")) {
            $this->markCanceled(CancelReason::notPaid());
        }
    }

    /*
     * on Payment\Paid
     */
    public function setPaid(): void
    {
        if ($this->isInStatus(Status::canceled()) || $this->isInStatus(Status::completed())) {
            return;
        }
        $this->isPaid = true;
    }

    public function complete(): void
    {
        if (!$this->isPaid) {
            throw new \RuntimeException();
        }

        $this->transitTo(Status::completed());
    }

    private function markCanceled(CancelReason $reason): void
    {
        $this->transitTo(Status::canceled());
        $this->cancelReason = $reason;
        $this->publish(new Canceled($this->id));
    }

    private function markChecked(): void
    {
        $this->transitTo(Status::checked());
        $this->publish(new Checked($this->id));
    }

    private function isInStatus(Status $status): bool
    {
        return $this->workflow->isInStatus($status);
    }

    private function transitTo(Status $status): void
    {
        $this->workflow->transitTo($status);
    }

    private function getStatusChangedAt(): \DateTimeImmutable
    {
        return $this->workflow->getChangedAt();
    }
}