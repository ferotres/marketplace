<?php


namespace App\Seller\Application\Service;


use App\Seller\Domain\Model\Seller;
use App\Seller\Domain\Model\SellerId;
use App\Seller\Domain\Model\SellerRepository;
use App\Shared\Application\ApplicationService;

class DeleteSeller implements ApplicationService
{
    /** @var SellerRepository */
    private $sellerReposiotry;

    public function __construct(SellerRepository $sellerReposiotry)
    {
        $this->sellerReposiotry = $sellerReposiotry;
    }

    public function execute(string $sellerId): bool
    {
        /** @var Seller $seller */
        $seller = $this->sellerReposiotry->withId(SellerId::create($sellerId));
        $this->sellerReposiotry->remove($seller);

        return true;
    }
}