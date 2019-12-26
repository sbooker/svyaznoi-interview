<?php

declare(strict_types=1);

namespace App\Bucket;

use Ramsey\Uuid\UuidInterface;

class Item
{
    private UuidInterface $productId;

    private int $price;

    private int $quantity;

    private int $totalPrice;

    public function __construct(UuidInterface $productId, int $price, int $quantity)
    {
        if ($price < 0) {
            throw new \InvalidArgumentException();
        }
        $this->productId = $productId;
        $this->price = $price;
        $this->setQuantity($quantity);
    }

    public function getProductId(): UuidInterface
    {
        return $this->productId;
    }

    public function setQuantity(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException();
        }
        $this->quantity = $quantity;
        $this->totalPrice = $this->calculateTotalPrice();
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getTotalPrice(): int
    {
        return $this->totalPrice;
    }

    private function calculateTotalPrice(): int
    {
        return $this->price * $this->quantity;
    }
}