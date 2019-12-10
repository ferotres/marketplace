<?php


namespace App\Customer\Domain\Model;


use App\Shared\Domain\Model\Id;

final class CustomerId extends Id
{
    public static function create(?string $id = null): CustomerId
    {
        return new self($id);
    }
}