<?php


namespace App\Seller\Domain\Model;

final class Stock
{
    /**  @var int */
    private $quantity;

    private function __construct(int $quantity = 0)
    {
        $this->quantity = $quantity;
    }

    public static function create(int $quantity = 0): Stock
    {
        return new self($quantity);
    }

    public function change(int $quantity): Stock
    {
        return new self($quantity);
    }

    public function value(): int
    {
        return $this->quantity;
    }

    public function __toString()
    {
        return (string)$this->quantity;
    }

    public function increase(int $units = 1): Stock
    {
        return new self($this->quantity + $units);
    }

    public function decrease(int $units = 1): Stock
    {
        return new self($this->quantity - $units);
    }
}