<?php


namespace App\Tests\UnitTest\Catalog\Application\Service;

use App\Catalog\Application\Service\UpdateProduct;
use App\Catalog\Domain\Model\Exception\ProductNotExist;
use App\Catalog\Domain\Model\Product;
use App\Catalog\Domain\Model\ProductId;
use App\Catalog\Domain\Model\ProductRepository;
use App\Catalog\Domain\Model\ProductWasCreated;
use App\Catalog\Domain\Model\ProductWasUpdated;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UpdateProductTest extends TestCase
{
    private $service;
    private $productRepository;

    protected function setUp()
    {
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->service = new UpdateProduct($this->productRepository);
    }

    /**
     * @test
     */
    public function whenUpdateAProductThatNotExistThenThrowException()
    {
        $this->productRepository
            ->method('withId')
            ->will($this->throwException(new ProductNotExist()));

        $this->expectException(ProductNotExist::class);
        $this->service->execute(Uuid::uuid4()->toString(), 'c', 'c');
    }

    /**
     * @test
     */
    public function whenUpdateAProductThenReturnProductInstanceUpdated()
    {
        $product = Product::create(ProductId::create(), 'c', 'c');
        $this->productRepository
            ->method('withId')
            ->willReturn($product);
        /** @var Product $productUpdated */
        $productUpdated = $this->service->execute($product->productId()->id(), 'a', 'a');
        $events = $productUpdated->unCommittedEvents();

        $this->assertInstanceOf(Product::class, $productUpdated);
        $this->assertEquals('a', $productUpdated->name());
        $this->assertEquals('a', $productUpdated->reference());

        $this->assertInstanceOf(ProductWasUpdated::class, array_pop($events));
    }

}