<?php


namespace App\Seller\Application\Service;


use App\Catalog\Domain\Model\ProductId;
use App\Catalog\Domain\Model\ProductRepository;
use App\Seller\Domain\Model\SellerId;
use App\Seller\Domain\Model\SellerProduct;
use App\Seller\Domain\Model\SellerProductId;
use App\Seller\Domain\Model\SellerProductRepository;
use App\Seller\Domain\Model\SellerRepository;
use App\Seller\Domain\Model\Stock;
use App\Shared\Domain\Model\Amount;

class AddProductToSeller
{
    /** @var SellerProductRepository */
    private $sellerProductRepository;
    /** @var ProductRepository */
    private $productRepository;
    /** @var SellerRepository */
    private $sellerRepository;

    public function __construct(
        SellerProductRepository $sellerProductRepository,
        ProductRepository $productRepository,
        SellerRepository $sellerRepository
    ) {
        $this->sellerProductRepository = $sellerProductRepository;
        $this->productRepository = $productRepository;
        $this->sellerRepository = $sellerRepository;
    }

    public function execute(string $sellerId, string $productId, int $stock, float $amount): SellerProduct
    {
        $sellerId = SellerId::create($sellerId);
        $productId = ProductId::create($productId);

        $this->sellerProductRepository->sellerProductExist($sellerId, $productId);
        $product = $this->productRepository->withId($productId);
        $seller = $this->sellerRepository->withId($sellerId);

        $sellerProduct = SellerProduct::create(
            SellerProductId::create(),
            $seller->sellerId(),
            $product->productId(),
            Stock::create($stock),
            Amount::create($amount)
        );

        $this->sellerProductRepository->save($sellerProduct);

        return $sellerProduct;
    }
}