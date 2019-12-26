<?php


namespace Notification;

use Ramsey\Uuid\UuidInterface;

interface OrderRepository
{
    public function get(UuidInterface $id): Order;
}