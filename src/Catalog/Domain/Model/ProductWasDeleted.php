<?php


namespace App\Catalog\Domain\Model;


use App\Shared\Domain\Model\Event;

final class ProductWasDeleted implements Event
{
    /** @var ProductId */
    private $productId;

    private function __construct(ProductId $productId)
    {
        $this->productId = $productId;
    }

    public static function create(ProductId $productId): ProductWasDeleted
    {
        return new self($productId);
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function id(): string
    {
        return Event::PRODUCT_WAS_DELETED;
    }
}