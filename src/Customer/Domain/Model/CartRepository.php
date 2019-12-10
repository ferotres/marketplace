<?php


namespace App\Customer\Domain\Model;


use App\Shared\Domain\Model\BaseRepository;

interface CartRepository extends BaseRepository
{
    public function withId(CartId $cartId): Cart;

    public function customerAlreadyHasACart(CustomerId $customerId);

    public function removeCartItem(CartId $cartId, CartItemId $cartItemId);
}