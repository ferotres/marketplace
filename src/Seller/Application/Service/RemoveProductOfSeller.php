<?php


namespace App\Seller\Application\Service;


use App\Seller\Domain\Model\SellerProductId;
use App\Seller\Domain\Model\SellerProductRepository;

class RemoveProductOfSeller
{
    /**
     * @var SellerProductRepository
     */
    private $sellerProductRepository;

    public function __construct(SellerProductRepository $sellerProductRepository)
    {
        $this->sellerProductRepository = $sellerProductRepository;
    }

    public function execute(string $sellerProductId): bool
    {
        $sellerProduct = $this->sellerProductRepository->withId(SellerProductId::create($sellerProductId));
        $this->sellerProductRepository->remove($sellerProduct);

        return true;
    }
}