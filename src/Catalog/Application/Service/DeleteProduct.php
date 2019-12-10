<?php


namespace App\Catalog\Application\Service;


use App\Catalog\Domain\Model\ProductId;
use App\Catalog\Domain\Model\ProductRepository;
use App\Shared\Application\ApplicationService;

class DeleteProduct implements ApplicationService
{
    /** @var ProductRepository */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function execute(string $productId): bool
    {
        $product = $this->productRepository->withId(ProductId::create($productId));
        $this->productRepository->remove($product);

        return true;
    }
}