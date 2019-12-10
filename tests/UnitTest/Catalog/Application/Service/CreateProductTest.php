<?php


namespace App\Tests\UnitTest\Catalog\Application\Service;


use App\Catalog\Application\Service\CreateProduct;
use App\Catalog\Domain\Model\Product;
use App\Catalog\Domain\Model\ProductRepository;
use App\Catalog\Domain\Model\ProductWasCreated;
use PHPUnit\Framework\TestCase;

class CreateProductTest extends TestCase
{
    private $service;
    private $productRepository;

    protected function setUp()
    {
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->service = new CreateProduct($this->productRepository);
    }

    /**
     * @test
     */
    public function whenCreateAProductThenReturnProductInstanceAndEmitEvent()
    {
        /** @var Product $product */
       $product =  $this->service->execute('crema', 'X123456');
       $events = $product->unCommittedEvents();
       $this->assertInstanceOf(Product::class, $product);
       $this->assertInstanceOf(ProductWasCreated::class, array_pop($events));
    }
}