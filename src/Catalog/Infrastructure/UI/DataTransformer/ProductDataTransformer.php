<?php


namespace App\Catalog\Infrastructure\UI\DataTransformer;


use App\Catalog\Domain\Model\Product;

class ProductDataTransformer
{
    /** @var Product */
    private $product;

    private function __construct(Product $product)
    {
        $this->product = $product;
    }

    public static function write($product): ProductDataTransformer
    {
        return new self($product);
    }

    public function read(): array
    {
        return [
            "id" => $this->product->productId()->id(),
            "name" => $this->product->name(),
            "reference" => $this->product->reference(),
        ];
    }
}