<?php


namespace App\Seller\Domain\Model;

use App\Catalog\Domain\Model\ProductId;
use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\Model\Amount;

class SellerProduct extends AggregateRoot
{

    /**  @var SellerProductId */
    private $sellerProductId;
    /** @var SellerId */
    private $sellerId;
    /**  @var ProductId */
    private $productId;
    /** @var Stock */
    private $stock;
    /** @var Amount */
    private $amount;

    private function __construct(
        SellerProductId $sellerProductId,
        SellerId $sellerId,
        ProductId $productId,
        Stock $stock,
        Amount $amount
    ) {
        $this->sellerProductId = $sellerProductId;
        $this->sellerId = $sellerId;
        $this->productId = $productId;
        $this->stock = $stock;
        $this->addEvent(ProductAddedBySeller::create($sellerProductId));
        $this->amount = $amount;
    }

    public static function create(
        SellerProductId $sellerProductId,
        SellerId $sellerId,
        ProductId $productId,
        Stock $stock,
        Amount $amount
    ): SellerProduct {
        return new self($sellerProductId, $sellerId, $productId, $stock, $amount);
    }

    public function sellerProductId(): SellerProductId
    {
        return $this->sellerProductId;
    }


    public function sellerId(): SellerId
    {
        return $this->sellerId;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function stock(): Stock
    {
        return $this->stock;
    }

    public function amount(): Amount
    {
        return $this->amount;
    }

    public function changeStock(Stock $stock): SellerProduct
    {
        $this->stock = $stock;
        $this->addEvent(StockWasChanged::create($this->sellerProductId()));

        return $this;
    }

    public function changeAmount(Amount $amount): SellerProduct
    {
        $this->amount = $amount;
        $this->addEvent(AmountWasChanged::create($this->sellerProductId()));

        return $this;
    }

    public function increaseStock(): SellerProduct
    {
        $this->stock = $this->stock()->increase();
        $this->addEvent(StockWasChanged::create($this->sellerProductId()));

        return $this;
    }

    public function decreaseStock(): SellerProduct
    {
        $this->stock = $this->stock()->decrease();
        $this->addEvent(StockWasChanged::create($this->sellerProductId()));

        return $this;
    }

    public function delete()
    {
        $this->addEvent(ProductRemovedBySeller::create($this->sellerProductId()));
    }
}