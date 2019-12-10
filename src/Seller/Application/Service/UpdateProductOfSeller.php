<?php


namespace App\Seller\Application\Service;


use App\Seller\Domain\Model\SellerProduct;
use App\Seller\Domain\Model\SellerProductId;
use App\Seller\Domain\Model\SellerProductRepository;

class UpdateProductOfSeller
{
    /** @var SellerProductRepository */
    private $sellerProductRepository;

    public function __construct(SellerProductRepository $sellerProductRepository)
    {
        $this->sellerProductRepository = $sellerProductRepository;
    }

    public function execute(string $sellerProductId, int $stock, float $amount): SellerProduct
    {
        $sellerProduct = $this->sellerProductRepository->withId(SellerProductId::create($sellerProductId));
        if ($sellerProduct->stock()->value() !== $stock) {
            $sellerProduct->changeStock($sellerProduct->stock()->change($stock));
        }
        if ($sellerProduct->amount()->value() !== $amount) {
            $sellerProduct->changeAmount($sellerProduct->amount()->change($amount));
        }
        $this->sellerProductRepository->save($sellerProduct);

        return $sellerProduct;
    }
}