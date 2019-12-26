<?php


namespace App\Order;


use Ramsey\Uuid\UuidInterface;

interface Warehouse
{
    public function getProductQuantity(UuidInterface $productId): int;
}