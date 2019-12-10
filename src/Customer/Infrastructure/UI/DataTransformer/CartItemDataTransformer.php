<?php


namespace App\Customer\Infrastructure\UI\DataTransformer;


use App\Customer\Domain\Model\CartItem;

class CartItemDataTransformer
{
    /**  @var CartItem */
    private $cartItem;

    private function __construct(CartItem $cartItem)
    {
        $this->cartItem = $cartItem;
    }

    public static function write(CartItem $cartItem): CartItemDataTransformer
    {
        return new self($cartItem);
    }

    public function read(): array
    {
        return [
            "id" => $this->cartItem->cartItemId()->id(),
            "sellerProduct" => $this->cartItem->sellerProductId()->id(),
            "amount" => $this->cartItem->amount()->value(),
            "quantity" => $this->cartItem->quantity(),
        ];
    }
}