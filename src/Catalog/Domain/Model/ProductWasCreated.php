<?php


namespace App\Catalog\Domain\Model;


use App\Shared\Domain\Model\Event;

final class ProductWasCreated implements Event
{
    /** @var ProductId */
    private $productId;

    private function __construct(ProductId $productId)
    {
        $this->productId = $productId;
    }

    public static function create(ProductId $productId): ProductWasCreated
    {
        return new self($productId);
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function id(): string
    {
        return Event::PRODUCT_WAS_CREATED;
    }
}