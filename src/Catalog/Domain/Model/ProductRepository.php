<?php


namespace App\Catalog\Domain\Model;

use App\Shared\Domain\Model\BaseRepository;

interface ProductRepository extends BaseRepository
{
    public function withId(ProductId $productId): Product;

    public function paginatedProducts(?int $offset = null);
}