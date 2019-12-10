<?php


namespace App\Seller\Application\Service;


use App\Customer\Domain\Model\CartRepository;
use App\Seller\Domain\Model\Exception\InsufficientStock;
use App\Seller\Domain\Model\SellerProductId;
use App\Seller\Domain\Model\SellerProductRepository;

class UpdateInventoryOfSeller
{
    /** @var SellerProductRepository */
    private $sellerProductRepository;
    /**  @var CartRepository */
    private $cartRepository;

    public function __construct(SellerProductRepository $sellerProductRepository, CartRepository $cartRepository)
    {
        $this->sellerProductRepository = $sellerProductRepository;
        $this->cartRepository = $cartRepository;
    }

    public function execute(string $sellerProductId, int $quantity)
    {
        $sellerProduct = $this->sellerProductRepository->withId(SellerProductId::create($sellerProductId));
        $newStock = $sellerProduct->stock()->decrease($quantity);
        if ($newStock->value() < 0) {
            throw new InsufficientStock($sellerProduct->sellerProductId()->id());
        }
        $sellerProduct->changeStock($newStock);

        $this->sellerProductRepository->save($sellerProduct);
    }
}