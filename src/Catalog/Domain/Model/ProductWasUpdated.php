<?php


namespace App\Catalog\Domain\Model;


use App\Shared\Domain\Model\Event;

final class ProductWasUpdated implements Event
{
    private $productId;
    private $name;
    private $reference;

    private function __construct(ProductId $productId, string $name, string $reference)
    {
        $this->productId = $productId;
        $this->name = $name;
        $this->reference = $reference;
    }

    public static function create(ProductId $productId, string $name, string $reference): ProductWasUpdated
    {
        return new self($productId, $name, $reference);
    }

    public function id(): string
    {
        return Event::PRODUCT_WAS_UPDATED;
    }
}