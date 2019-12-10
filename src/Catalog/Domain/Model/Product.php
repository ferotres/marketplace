<?php


namespace App\Catalog\Domain\Model;


use App\Shared\Domain\Model\AggregateRoot;

class Product extends AggregateRoot
{
    /** @var ProductId */
    private $productId;
    /** @var string */
    private $name;
    /**  @var string */
    private $reference;

    private function __construct(ProductId $productId, string $name, string $reference)
    {
        $this->productId = $productId;
        $this->name = $name;
        $this->reference = $reference;
        $this->addEvent(ProductWasCreated::create($productId));

    }

    public static function create(ProductId $productId, string $name, string $reference)
    {
        return new self($productId, $name, $reference);
    }

    public function updateProduct(string $name, string $reference): Product
    {
        $this->reference = $reference;
        $this->name = $name;
        $this->addEvent(ProductWasUpdated::create($this->productId, $this->name, $reference));

        return $this;
    }

    public function delete()
    {
        $this->addEvent(ProductWasDeleted::create($this->productId()));
    }

    /**
     * @return ProductId
     */
    public function productId(): ProductId
    {
        return $this->productId;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function reference(): string
    {
        return $this->reference;
    }

}