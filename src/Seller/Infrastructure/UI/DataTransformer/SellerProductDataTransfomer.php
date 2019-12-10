<?php


namespace App\Seller\Infrastructure\UI\DataTransformer;


use App\Seller\Domain\Model\SellerProduct;

class SellerProductDataTransfomer
{
    /** @var SellerProduct */
    private $sellerProduct;

    private function __construct(SellerProduct $sellerProduct)
    {
        $this->sellerProduct = $sellerProduct;
    }

    public static function write($sellerProduct): SellerProductDataTransfomer
    {
        return new self($sellerProduct);
    }

    public function read(): array
    {
        return [
            "id" => $this->sellerProduct->sellerProductId()->id(),
            "sellerId" => $this->sellerProduct->sellerId()->id(),
            "productId" => $this->sellerProduct->productId()->id(),
            "stock" => $this->sellerProduct->stock()->value(),
            "amount" => $this->sellerProduct->amount()->value(),
        ];
    }
}