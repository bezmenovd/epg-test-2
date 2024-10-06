<?php

namespace App\Models;

class OrderProduct
{
    public function __construct(
        public int $id,
        public int $orderId,
        public string $name,
        public float $price,
        public int $quantity,
        public float $discountPercent,
    ) {}

    public function getTotal(): float
    {
        return $this->price * $this->quantity * (100 - $this->discountPercent);
    }
}
