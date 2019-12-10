<?php


namespace App\Catalog\Application\Service;


use App\Catalog\Domain\Model\ProductRepository;
use App\Shared\Application\ApplicationService;

class RetrieveProductCatalog implements ApplicationService
{
    /**  @var ProductRepository */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function execute(?int $offset = 0): array
    {
        return $this->productRepository->paginatedProducts($offset);
    }
}