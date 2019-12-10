<?php


namespace App\Tests\UnitTest\Customer\Domain\Model;


use App\Customer\Domain\Model\Cart;
use App\Customer\Domain\Model\CartId;
use App\Customer\Domain\Model\CartItem;
use App\Customer\Domain\Model\CustomerId;
use App\Customer\Domain\Model\Exception\CartIsCommited;
use App\Customer\Domain\Model\Exception\CartIsEmpty;
use App\Seller\Domain\Model\SellerProductId;
use App\Shared\Domain\Model\Amount;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    /**
     * @test
     */
    public function whenCreateACartThenReturnInstance() :Cart
    {
        $cart = Cart::create(CartId::create(), CustomerId::create());
        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertEquals(0, $cart->amount()->value());
        $this->assertFalse( $cart->isCommitted());
        return  $cart;
    }

    /**
     * @test
     * @depends whenCreateACartThenReturnInstance
     */
    public function whenCommitAEmptyCartThenTrowsException(Cart $cart)
    {
        $this->expectException(CartIsEmpty::class);
        $cart->commit();
    }

    /**
     * @test
     * @depends whenCreateACartThenReturnInstance
     */
    public function whenAddProductToCartThenCalculateAmount(Cart $cart)
    {
        $cart->addProductToCart(SellerProductId::create(), Amount::create(25), 2);
        $this->assertEquals(50, $cart->amount()->value());
        $this->assertCount(1, $cart->cartItems());
        return $cart;
    }

    /**
     * @test
     * @depends whenAddProductToCartThenCalculateAmount
     */
    public function whenRemoveAProductThenCalculateAmount(Cart $cart)
    {
        /** @var CartItem $cartItem */
        $cartItem = $cart->cartItems()[0];
        $cart->removeProductOfCart($cartItem->cartItemId());
        $this->assertEquals(0, $cart->amount()->value());
        $this->assertCount(0, $cart->cartItems());
    }

    /**
     * @test
     * @depends whenAddProductToCartThenCalculateAmount
     */
    public function whenAddOrRemoveProductToCommitedCartThenThrowsException(Cart $cart)
    {
        $cart->addProductToCart(SellerProductId::create(), Amount::create(25), 2);
        $cart->commit();
        $this->expectException(CartIsCommited::class);
        $cart->addProductToCart(SellerProductId::create(), Amount::create(25), 2);
    }

    /**
     * @test
     * @depends whenAddProductToCartThenCalculateAmount
     */
    public function whenRemoveProductToCommitedCartThenThrowsException(Cart $cart)
    {
        /** @var CartItem $cartItem */
        $cartItem = $cart->cartItems()[1];
        $this->expectException(CartIsCommited::class);
        $cart->removeProductOfCart($cartItem->cartItemId());
    }


}