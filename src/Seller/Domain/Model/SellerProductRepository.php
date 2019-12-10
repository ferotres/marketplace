<?php


namespace App\Seller\Domain\Model;

use App\Catalog\Domain\Model\ProductId;
use App\Shared\Domain\Model\BaseRepository;

interface SellerProductRepository extends BaseRepository
{
    public function withId(SellerProductId $sellerProductId): SellerProduct;

    public function sellerProductExist(SellerId $sellerId, ProductId $productId);

    public function bestSellerForAProduct(ProductId $productId, int $quantity): SellerProduct;

    public function availableProductForCommit(SellerProductId $sellerProductId, int $quantity);

}