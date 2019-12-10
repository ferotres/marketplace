<?php


namespace App\Shared\Domain\Model;


class Amount
{
    /** @var float */
    private $amount;

    private function __construct(float $amount)
    {
        $this->amount = $amount;
    }

    public static function create(float $amount): Amount
    {
        return new self($amount);
    }

    public function change(float $amount): Amount
    {
        return new self($amount);
    }

    public function __toString(): string
    {
        return (string)$this->amount;
    }

    public function value(): float
    {
        return $this->amount;
    }

    public function increaseAmount(float $amount): Amount
    {
        return $this->change($this->value() + $amount);
    }

    public function decreaseAmount(float $amount): Amount
    {
        $amount = $this->value() - $amount;

        return $this->change($amount > 0 ? $amount : 0);
    }
}