<?php


namespace App\Seller\Domain\Model;


use App\Shared\Domain\Model\Id;

final class SellerId extends Id
{
    public static function create(?string $id = null): SellerId
    {
        return new self($id);
    }
}