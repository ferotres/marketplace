<?php

namespace App\Customer\Domain\Model;

use App\Customer\Domain\Model\Exception\CartIsCommited;
use App\Customer\Domain\Model\Exception\CartIsEmpty;
use App\Seller\Domain\Model\SellerProductId;
use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\Model\Amount;

class Cart extends AggregateRoot
{
    /** @var CartId */
    private $cartId;
    /** @var CustomerId */
    private $customerId;
    /** @var Amount */
    private $amount;
    /** @var CartItem[] */
    private $cartItems;
    /** @var bool */
    private $committed;

    private function __construct(CartId $cartId, CustomerId $customerId)
    {
        $this->cartId = $cartId;
        $this->customerId = $customerId;
        $this->amount = Amount::create(0);
        $this->cartItems = [];
        $this->committed = false;
        $this->addEvent(CartWasCreated::create($this->cartId()));
    }

    public static function create(CartId $cartId, CustomerId $customerId): Cart
    {
        return new self($cartId, $customerId);
    }

    public function cartId(): CartId
    {
        return $this->cartId;
    }

    public function customerId(): CustomerId
    {
        return $this->customerId;
    }

    public function amount(): Amount
    {
        return $this->amount;
    }

    public function isCommitted(): bool
    {
        return $this->committed;
    }

    public function commit(): Cart
    {
        if (count($this->cartItems()) === 0) {
            throw new CartIsEmpty();
        }
        $this->committed = true;
        $this->addEvent(CartWasCommitted::create($this->cartId()));

        return $this;
    }

    public function cartItems(): array
    {
        return $this->cartItems;
    }

    public function addProductToCart(SellerProductId $sellerProductId, Amount $amount, int $quantity) :Cart
    {
        if ($this->isCommitted()) {
            throw new CartIsCommited();
        }
        $cartItem = CartItem::create(CartItemId::create(), $this->cartId(), $sellerProductId, $amount, $quantity);
        $this->cartItems[] = $cartItem;
        $this->calculateAmountOfCart();

        return $this;
    }

    public function removeProductOfCart(CartItemId $cartItemId) :Cart
    {
        if ($this->isCommitted()) {
            throw new CartIsCommited();
        }

        $keyToRemove = null;
        foreach ($this->cartItems() as $key => $cartItem) {
            if ($cartItem->cartItemId()->equalTo($cartItemId)) {
                $keyToRemove = $key;
                break;
            }
        }
        if (!is_null($keyToRemove)) {
            unset($this->cartItems[$keyToRemove]);
            $this->calculateAmountOfCart();
        }
        return $this;
    }

    public function delete()
    {
        $this->addEvent(CartWasRemoved::create($this->cartId()));
    }

    private function calculateAmountOfCart()
    {
        $amount = array_reduce(
            $this->cartItems(),
            function ($carry, CartItem $cartItem) {
                $carry += $cartItem->amount()->value() * $cartItem->quantity();

                return $carry;
            },
            0
        );

        $this->amount = $this->amount()->change($amount);
    }

    public function setCardItems(array $cartItems): Cart
    {
        $this->cartItems = $cartItems;

        return $this;
    }

}