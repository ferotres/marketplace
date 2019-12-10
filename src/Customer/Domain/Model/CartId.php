<?php


namespace App\Customer\Domain\Model;


use App\Shared\Domain\Model\Id;

final class CartId extends Id
{
    public static function create(?string $id = null): CartId
    {
        return new self($id);
    }
}