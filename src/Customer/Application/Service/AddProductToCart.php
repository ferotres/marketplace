<?php


namespace App\Customer\Application\Service;


use App\Catalog\Domain\Model\ProductId;
use App\Customer\Domain\Model\Cart;
use App\Customer\Domain\Model\CartId;
use App\Customer\Domain\Model\CartRepository;
use App\Seller\Domain\Model\SellerProductRepository;

class AddProductToCart
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

    public function execute(string $cartId, string $productId, int $quantity): Cart
    {
        $cart = $this->cartRepository->withId(CartId::create($cartId));
        $sellerProduct = $this->sellerProductRepository->bestSellerForAProduct(
            ProductId::create($productId),
            $quantity
        );

        $sellerProductId = $sellerProduct->sellerProductId();
        $cart->addProductToCart($sellerProductId, $sellerProduct->amount(), $quantity);

        $this->cartRepository->save($cart);

        return $cart;
    }
}