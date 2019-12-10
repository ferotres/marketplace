<?php


namespace App\Tests\UnitTest\Seller\Application\Service;


use App\Catalog\Domain\Model\Exception\ProductNotExist;
use App\Catalog\Domain\Model\Product;
use App\Catalog\Domain\Model\ProductId;
use App\Catalog\Domain\Model\ProductRepository;
use App\Seller\Application\Service\AddProductToSeller;
use App\Seller\Domain\Model\Exception\SellerNotExist;
use App\Seller\Domain\Model\Exception\SellerProductAlreadyExist;
use App\Seller\Domain\Model\Seller;
use App\Seller\Domain\Model\SellerId;
use App\Seller\Domain\Model\SellerProduct;
use App\Seller\Domain\Model\SellerProductRepository;
use App\Seller\Domain\Model\SellerRepository;
use App\Seller\Domain\Model\Stock;
use App\Shared\Domain\Model\Amount;
use App\Shared\Domain\Model\Email;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class AddProductToSellerTest extends TestCase
{

    private $sellerRepository;
    private $productRepository;
    private $sellerProductRepository;
    private $service;

    protected function setUp()
    {
       $this->productRepository = $this->createMock(ProductRepository::class);
       $this->sellerRepository = $this->createMock(SellerRepository::class);
       $this->sellerProductRepository = $this->createMock(SellerProductRepository::class);
       $this->service = new AddProductToSeller($this->sellerProductRepository, $this->productRepository, $this->sellerRepository);
    }

    /**
     * @test
     */
    public function whenAddProductToSellerInventoryThenReturnSellerProductInstance()
    {
        $this->sellerProductRepository
            ->method('sellerProductExist')
            ->willReturn(null);

        $this->productRepository
            ->method('withId')
            ->willReturn(Product::create(ProductId::create(), 'c', 'c'));

        $this->sellerRepository
            ->method('withId')
            ->willReturn(Seller::create(SellerId::create(), 'c', Email::create('test@test.com')));

        $sellerProduct = $this->service->execute(Uuid::uuid4()->toString(), Uuid::uuid4()->toString(), 10, 15.70);

        $this->assertInstanceOf(SellerProduct::class, $sellerProduct);
        $this->assertInstanceOf(Stock::class, $sellerProduct->stock());
        $this->assertInstanceOf(Amount::class, $sellerProduct->amount());

        return $sellerProduct;
    }

    /**
     * @test
     */
    public function whenAddProductThatAlreadyExistThenTrowsException()
    {
        $this->sellerProductRepository
            ->method('sellerProductExist')
            ->will($this->throwException(new SellerProductAlreadyExist()));

        $this->expectException(SellerProductAlreadyExist::class);
        $this->service->execute(Uuid::uuid4()->toString(), Uuid::uuid4()->toString(), 10, 15.70);
    }

    /**
     * @test
     */
    public function whenAddProductThatNotExistThenTrowsAException()
    {
        $this->productRepository
            ->method('withId')
            ->will($this->throwException(new ProductNotExist()));

        $this->expectException(ProductNotExist::class);
        $this->service->execute(Uuid::uuid4()->toString(), Uuid::uuid4()->toString(), 10, 15.70);
    }

    /**
     * @test
     */
    public function whenAddProductAndSellerNotExistThenTrowsAException()
    {
        $this->sellerRepository
            ->method('withId')
            ->will($this->throwException(new SellerNotExist()));

        $this->expectException(SellerNotExist::class);
        $this->service->execute(Uuid::uuid4()->toString(), Uuid::uuid4()->toString(), 10, 15.70);
    }

}