<?php


namespace App\Customer\Domain\Model;

use App\Seller\Domain\Model\SellerProductId;
use App\Shared\Domain\Model\Amount;

class CartItem
{
    /** @var CartItemId */
    private $cartItemId;
    /** @var SellerProductId */
    private $sellerProductId;
    /** @var Amount */
    private $amount;
    /** @var int */
    private $quantity;
    /** @var CartId */
    private $cartId;

    private function __construct(
        CartItemId $cartItemId,
        CartId $cartId,
        SellerProductId $sellerProductId,
        Amount $amount,
        int $quantity
    ) {
        $this->cartItemId = $cartItemId;
        $this->sellerProductId = $sellerProductId;
        $this->amount = $amount;
        $this->quantity = $quantity;
        $this->cartId = $cartId;
    }

    public static function create(
        CartItemId $cartItemId,
        CartId $cartId,
        SellerProductId $sellerProductId,
        Amount $amount,
        int $quantity
    ): CartItem {
        return new self($cartItemId, $cartId, $sellerProductId, $amount, $quantity);
    }

    public function cartItemId(): CartItemId
    {
        return $this->cartItemId;
    }

    public function cartId(): CartId
    {
        return $this->cartId;
    }

    public function sellerProductId(): SellerProductId
    {
        return $this->sellerProductId;
    }

    public function amount(): Amount
    {
        return $this->amount;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }
}