<?php


namespace App\Seller\Domain\Model;


use App\Shared\Domain\Model\Id;

final class SellerProductId extends Id
{
    public static function create(?string $id = null): SellerProductId
    {
        return new self($id);
    }
}