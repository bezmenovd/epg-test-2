<?php

namespace App\Models;

class Order
{
    /**
     * @param int $id
     * @param OrderProduct[] $products
     */
    public function __construct(
        public int $id,
        public array $products,
    ) {}

    public function getTotal(): float
    {
        return array_reduce($this->products, fn(float $total, OrderProduct $op) => $total + $op->getTotal(), 0);
    }
}
