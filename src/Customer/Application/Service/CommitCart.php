<?php


namespace App\Customer\Application\Service;


use App\Customer\Domain\Model\Cart;
use App\Customer\Domain\Model\CartId;
use App\Customer\Domain\Model\CartItem;
use App\Customer\Domain\Model\CartRepository;
use App\Seller\Domain\Model\SellerProductRepository;

class CommitCart
{
    /** @var CartRepository */
    private $cartRepository;
    /** @var SellerProductRepository */
    private $sellerProductRepository;

    public function __construct(CartRepository $cartRepository, SellerProductRepository $sellerProductRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->sellerProductRepository = $sellerProductRepository;
    }

    public function execute(string $cartId): Cart
    {
        /** @var Cart $cart */
        $cart = $this->cartRepository->withId(CartId::create($cartId));

        /** @var CartItem $cartItem */
        foreach ($cart->cartItems() as $cartItem) {
            $this->sellerProductRepository->availableProductForCommit(
                $cartItem->sellerProductId(),
                $cartItem->quantity()
            );
        }

        $cart->commit();

        $this->cartRepository->save($cart);

        return $cart;
    }
}