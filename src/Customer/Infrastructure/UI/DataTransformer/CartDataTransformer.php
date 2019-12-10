<?php


namespace App\Customer\Infrastructure\UI\DataTransformer;


use App\Customer\Domain\Model\Cart;

class CartDataTransformer
{
    /**  @var Cart */
    private $cart;

    private function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public static function write(Cart $cart): CartDataTransformer
    {
        return new self($cart);
    }

    public function read(): array
    {
        $response = [
            "id" => $this->cart->cartId()->id(),
            "customerId" => $this->cart->customerId()->id(),
            "amount" => $this->cart->amount()->value(),
            "committed" => $this->cart->isCommitted(),
            "cartItems" => [],
        ];

        foreach ($this->cart->cartItems() as $cartItem) {
            $response['cartItems'][] = CartItemDataTransformer::write($cartItem)->read();
        }

        return $response;
    }
}