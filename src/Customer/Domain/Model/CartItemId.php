<?php


namespace App\Customer\Domain\Model;


use App\Shared\Domain\Model\Id;

class CartItemId extends Id
{

    public static function create(string $id = null): CartItemId
    {
        return new self($id);
    }
}