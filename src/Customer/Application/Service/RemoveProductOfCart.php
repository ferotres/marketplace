<?php


namespace App\Customer\Application\Service;


use App\Customer\Domain\Model\Cart;
use App\Customer\Domain\Model\CartId;
use App\Customer\Domain\Model\CartItemId;
use App\Customer\Domain\Model\CartRepository;

class RemoveProductOfCart
{
    /** @var CartRepository */
    private $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function execute(string $cartId, string $cartItemId): Cart
    {
        $cart = $this->cartRepository->withId(CartId::create($cartId));
        $cart->removeProductOfCart(CartItemId::create($cartItemId));
        $this->cartRepository->save($cart);
        $this->cartRepository->removeCartItem(CartId::create($cartId), CartItemId::create($cartItemId));
        return $cart;
    }
}