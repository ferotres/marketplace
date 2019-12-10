<?php


namespace App\Catalog\Domain\Model;


use App\Shared\Domain\Model\Id;

final class ProductId extends Id
{
    public static function create(?string $id = null): ProductId
    {
        return new self($id);
    }
}