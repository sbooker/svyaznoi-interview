<?php

declare(strict_types=1);

namespace Notification;

use App\Common\CancelReason;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Notification
{
    private UuidInterface $id;

    private UuidInterface $buyerId;

    private string $message;

    public function __construct(UuidInterface $id, UuidInterface $buyerId, string $message)
    {
        $this->id = $id;
        $this->buyerId = $buyerId;
        $this->message = $message;
    }

    /*
     * on Order\Cancelled
     */
    public static function createOrderCancelled(UuidInterface $orderId, OrderRepository $orderRepository): self
    {
        $order = $orderRepository->get($orderId);
        switch ($order->getCancelReason()) {
            case CancelReason::notPaid():
                $message = "Order not paid. Canceled.";
                break;
            case CancelReason::insufficientProducts():
                $message = "Insufficient product(s). Order cancelled";
                break;
            default:
                throw new \RuntimeException();
        }

        return new self(Uuid::uuid4(), $order->getBuyerId(), $message);
    }
}