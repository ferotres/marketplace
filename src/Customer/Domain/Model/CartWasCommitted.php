<?php


namespace App\Customer\Domain\Model;


use App\Shared\Domain\Model\Event;

class CartWasCommitted implements Event
{

    /** @var CartId */
    private $cartId;

    private function __construct(CartId $cartId)
    {
        $this->cartId = $cartId;
    }

    public static function create(CartId $cartId): CartWasCommitted
    {
        return new self($cartId);
    }

    public function cartId(): CartId
    {
        return $this->cartId;
    }

    public function id(): string
    {
        return Event::CART_WAS_COMMITTED;
    }
}