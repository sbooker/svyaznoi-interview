<?php

declare(strict_types=1);

namespace App\Order;

use Ramsey\Uuid\UuidInterface;

class Item
{
    private UuidInterface $productId;

    private int $price;

    private int $quantity;

    private int $totalPrice;

    private ?int $warehoseQuantity = null;

    public function __construct(UuidInterface $productId, int $price, int $quantity, int $totalPrice)
    {
        if ($price < 0) {
            throw new \InvalidArgumentException();
        }
        if ($totalPrice < 0) {
            throw new \InvalidArgumentException();
        }
        $this->productId = $productId;
        $this->price = $price;
        $this->setQuantity($quantity);
        $this->totalPrice = $totalPrice;
    }

    public function setWarehouseQuantity(int $quantity): void
    {
        $this->warehoseQuantity = $quantity;
    }

    public function existsInWarehouse(): bool
    {
        if (null === $this->warehoseQuantity) {
            throw new \RuntimeException();
        }
        return $this->quantity >= $this->warehoseQuantity;
    }

    private function setQuantity(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException();
        }
        $this->quantity = $quantity;
    }


}