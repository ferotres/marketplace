<?php


namespace App\Seller\Domain\Model;


use App\Shared\Domain\Model\BaseRepository;
use App\Shared\Domain\Model\Email;

interface SellerRepository extends BaseRepository
{
    public function withId(SellerId $sellerId): Seller;

    public function withEmail(Email $email): ?Seller;
}