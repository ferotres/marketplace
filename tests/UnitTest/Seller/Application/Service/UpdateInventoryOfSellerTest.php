<?php


namespace App\Tests\UnitTest\Seller\Application\Service;


use App\Catalog\Domain\Model\ProductId;
use App\Customer\Domain\Model\CartRepository;
use App\Seller\Application\Service\UpdateInventoryOfSeller;
use App\Seller\Domain\Model\Exception\InsufficientStock;
use App\Seller\Domain\Model\SellerId;
use App\Seller\Domain\Model\SellerProduct;
use App\Seller\Domain\Model\SellerProductId;
use App\Seller\Domain\Model\SellerProductRepository;
use App\Seller\Domain\Model\Stock;
use App\Shared\Domain\Model\Amount;
use PHPUnit\Framework\TestCase;

class UpdateInventoryOfSellerTest extends TestCase
{
    private $sellerProductRepository;
    private $cartRepository;
    private $service;

    protected function setUp()
    {
        $this->sellerProductRepository = $this->createMock(SellerProductRepository::class);
        $this->cartRepository = $this->createMock(CartRepository::class);
        $this->service = new UpdateInventoryOfSeller($this->sellerProductRepository, $this->cartRepository);
    }

    /**
     * @test
     */
    public function whenUpdateInventoryThenReturnInventoryWithStockUpdated() :SellerProduct
    {
        $sellerProduct = $this->getSellerProduct();

        $this->sellerProductRepository
            ->method('withId')
            ->willReturn($sellerProduct);

        $this->service->execute($sellerProduct->sellerProductId(), 8);

        $this->assertEquals(2, $sellerProduct->stock()->value());

        return $sellerProduct;
    }

    /**
     * @test
     * @depends whenUpdateInventoryThenReturnInventoryWithStockUpdated
     */
    public function whenUpdateInventoryWithoutstockThenThrowsException(SellerProduct $sellerProduct)
    {

        $this->sellerProductRepository
            ->method('withId')
            ->willReturn($sellerProduct);

        $this->expectException(InsufficientStock::class);
        $this->service->execute($sellerProduct->sellerProductId(), 8);

    }

    private function getSellerProduct():SellerProduct
    {
        return SellerProduct::create(
            SellerProductId::create(),
            SellerId::create(),
            ProductId::create(),
            Stock::create(10),
            Amount::create(25)
        );
    }
}