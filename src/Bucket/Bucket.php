<?php

declare(strict_types=1);

namespace App\Bucket;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Bucket
{
    /** @var Item[]  */
    private array $items;

    private int $totalPrice = 0;

    public function __construct()
    {
    }

    public function add(UuidInterface $productId, int $price, int $quantity): void
    {
        $this->items[$productId->toString()] = new Item($productId, $price, $quantity);
        $this->updateTotalPrice();
    }

    public function has(UuidInterface $productId): bool
    {
        return isset($this->items[$productId->toString()]);
    }

    public function remove(UuidInterface $productId): void
    {
        if ($this->has($productId)) {
            unset($this->items[$productId->toString()]);
            $this->updateTotalPrice();
        }
    }

    public function removeAll(): void
    {
        $this->items = [];
        $this->updateTotalPrice();
    }

    public function setItemQuantity(UuidInterface $productId, int $quantity): void
    {
        if (!$this->has($productId)) {
            throw new \InvalidArgumentException();
        }

        $this->items[$productId->toString()]->setQuantity($quantity);
        $this->updateTotalPrice();
    }

    public function order(PaymentOrder $paymentOrder): Order
    {
        $order =
            new Order(
                Uuid::uuid4(),
                $this->buyerId,
                array_map(
                    function (Item $bucketItem): OrderItem { return new OrderItem(
                        $bucketItem->getProductId(),
                        $bucketItem->getQuantity(),
                        $bucketItem->getPrice(),
                        $bucketItem->getTotalPrice()
                    );},
                    $this->items
                ),
                $this->totalPrice,
                $paymentOrder);
        $this->removeAll();

        return $order;
    }

    private function updateTotalPrice(): void
    {
        $this->totalPrice = $this->calculateTotalPrice();
    }

    private function calculateTotalPrice(): int
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total+= $item->getTotalPrice();
        }

        return $total;
    }
}