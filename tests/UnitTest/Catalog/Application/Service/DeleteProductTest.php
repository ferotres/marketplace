<?php


namespace App\Tests\UnitTest\Catalog\Application\Service;


use App\Catalog\Application\Service\DeleteProduct;
use App\Catalog\Domain\Model\Exception\ProductNotExist;
use App\Catalog\Domain\Model\Product;
use App\Catalog\Domain\Model\ProductId;
use App\Catalog\Domain\Model\ProductRepository;
use App\Catalog\Domain\Model\ProductWasDeleted;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class DeleteProductTest extends TestCase
{
    private $service;
    private $productRepository;

    protected function setUp()
    {
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->service = new DeleteProduct($this->productRepository);
    }

    /**
     * @test
     */
    public function whenProductNotExistThenThrowException()
    {
        $this->expectException(ProductNotExist::class);

        $this->productRepository
            ->method('withId')
            ->will($this->throwException(new ProductNotExist()));

        $this->service->execute(Uuid::uuid4()->toString());
    }

    /**
     * @test
     */
    public function whenDeleteAProductThenReturnTrue()
    {
        $product = Product::create(ProductId::create(), 'c', 'c');
        $this->productRepository
            ->method('withId')
            ->willReturn($product);

        $success = $this->service->execute($product->productId()->id());

        $this->assertTrue($success);
    }
}