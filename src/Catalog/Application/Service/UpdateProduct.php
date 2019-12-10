<?php


namespace App\Catalog\Application\Service;


use App\Catalog\Domain\Model\Product;
use App\Catalog\Domain\Model\ProductId;
use App\Catalog\Domain\Model\ProductRepository;
use App\Shared\Application\ApplicationService;

class UpdateProduct implements ApplicationService
{
    /**  @var ProductRepository */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function execute(string $productId, string $name, string $reference): Product
    {
        $product = $this->productRepository->withId(ProductId::create($productId));
        $product->updateProduct($name, $reference);
        $this->productRepository->save($product);

        return $product;
    }
}