<?php


namespace App\Customer\Domain\Model;


use App\Shared\Domain\Model\Event;

class CartWasRemoved implements Event
{

    /** @var CartId */
    private $cartId;

    private function __construct(CartId $cartId)
    {
        $this->cartId = $cartId;
    }

    public static function create(CartId $cartId): CartWasRemoved
    {
        return new self($cartId);
    }

    public function cartId(): CartId
    {
        return $this->cartId;
    }

    public function id(): string
    {
        return Event::CART_WAS_REMOVED;
    }
}