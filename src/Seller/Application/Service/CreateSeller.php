<?php


namespace App\Seller\Application\Service;


use App\Seller\Domain\Model\Exception\SellerAreadyExist;
use App\Seller\Domain\Model\Seller;
use App\Seller\Domain\Model\SellerId;
use App\Seller\Domain\Model\SellerRepository;
use App\Shared\Application\ApplicationService;
use App\Shared\Domain\Model\Email;

class CreateSeller implements ApplicationService
{
    /** @var SellerRepository */
    private $sellerReposiotry;

    public function __construct(SellerRepository $sellerReposiotry)
    {
        $this->sellerReposiotry = $sellerReposiotry;
    }

    public function execute(string $name, string $email): Seller
    {
        $email = Email::create($email);
        $sellerExist = $this->sellerReposiotry->withEmail($email);
        if ($sellerExist) {
            throw new SellerAreadyExist();
        }
        $seller = Seller::create(SellerId::create(), $name, $email);
        $this->sellerReposiotry->save($seller);

        return $seller;
    }
}