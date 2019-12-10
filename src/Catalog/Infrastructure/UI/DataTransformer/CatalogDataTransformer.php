<?php


namespace App\Catalog\Infrastructure\UI\DataTransformer;


use App\Catalog\Domain\Model\Product;

class CatalogDataTransformer
{
    /** @var array */
    private $paginatedResult;

    private function __construct(array $paginatedResult)
    {
        $this->paginatedResult = $paginatedResult;
    }

    public static function write($paginatedResult): CatalogDataTransformer
    {
        return new self($paginatedResult);
    }

    public function read(): array
    {
        return [
            "total" => $this->paginatedResult['total'],
            "next_offset" => $this->paginatedResult['next_offset'],
            "records" => array_map(
                function (Product $product) {
                    return ProductDataTransformer::write($product)->read();
                },
                $this->paginatedResult['records']
            ),
        ];
    }
}