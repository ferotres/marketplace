<?php


namespace App\Catalog\Application\Service;


use App\Catalog\Domain\Model\Product;
use App\Catalog\Domain\Model\ProductId;
use App\Catalog\Domain\Model\ProductRepository;
use App\Shared\Application\ApplicationService;

class CreateProduct implements ApplicationService
{
    /**  @var ProductRepository */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function execute(string $name, string $reference): Product
    {
        $product = Product::create(ProductId::create(), $name, $reference);
        $this->productRepository->save($product);

        return $product;
    }
}