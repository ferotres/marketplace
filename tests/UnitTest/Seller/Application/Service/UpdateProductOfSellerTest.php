<?php


namespace App\Tests\UnitTest\Seller\Application\Service;


use App\Catalog\Domain\Model\ProductId;
use App\Seller\Application\Service\UpdateProductOfSeller;
use App\Seller\Domain\Model\AmountWasChanged;
use App\Seller\Domain\Model\Exception\SellerProductNotExist;
use App\Seller\Domain\Model\SellerId;
use App\Seller\Domain\Model\SellerProduct;
use App\Seller\Domain\Model\SellerProductId;
use App\Seller\Domain\Model\SellerProductRepository;
use App\Seller\Domain\Model\Stock;
use App\Seller\Domain\Model\StockWasChanged;
use App\Shared\Domain\Model\Amount;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UpdateProductOfSellerTest extends TestCase
{
    private $sellerProductRepository;
    private $service;

    protected function setUp()
    {
        $this->sellerProductRepository = $this->createMock(SellerProductRepository::class);
        $this->service = new UpdateProductOfSeller($this->sellerProductRepository);
    }

    /**
     * @test
     */
    public function whenUpdateProductOfSellerInventoryThenReturnInstance() :SellerProduct
    {
        /** @var SellerProduct $sellerProduct */
        $sellerProduct = SellerProduct::create(
            SellerProductId::create(),
            SellerId::create(),
            ProductId::create(),
            Stock::create(10),
            Amount::create(15)
        );

        $this->sellerProductRepository
            ->method('withId')
            ->willReturn($sellerProduct);

        /** @var SellerProduct $sellerProductUpdated */
        $sellerProductUpdated = $this->service->execute($sellerProduct->sellerProductId()->id(), 20, 20);

        $events = $sellerProduct->unCommittedEvents();

        $this->assertEquals(20, $sellerProductUpdated->stock()->value());
        $this->assertEquals(20, $sellerProductUpdated->amount()->value());

        $this->assertInstanceOf(AmountWasChanged::class, array_pop($events));
        $this->assertInstanceOf(StockWasChanged::class, array_pop($events));

        return $sellerProductUpdated;
    }

    /**
     * @test
     * @depends whenUpdateProductOfSellerInventoryThenReturnInstance
     */
    public function whenOnlyChangeAmountThenEmitAmountchangetEvent(SellerProduct $sellerProduct)
    {
        $this->sellerProductRepository
            ->method('withId')
            ->willReturn($sellerProduct);

        /** @var SellerProduct $sellerProductUpdated */
        $sellerProductUpdated = $this->service->execute($sellerProduct->sellerProductId()->id(), 20, 25);

        $events = $sellerProduct->unCommittedEvents();

        $this->assertEquals(20, $sellerProductUpdated->stock()->value());
        $this->assertEquals(25, $sellerProductUpdated->amount()->value());

        $this->assertInstanceOf(AmountWasChanged::class, array_pop($events));
        $this->assertNotInstanceOf(StockWasChanged::class, array_pop($events));

        return $sellerProductUpdated;
    }

    /**
     * @test
     * @depends whenOnlyChangeAmountThenEmitAmountchangetEvent
     */
    public function whenOnlyChangeStockThenEmitStockChangetEvent(SellerProduct $sellerProduct)
    {
        $this->sellerProductRepository
            ->method('withId')
            ->willReturn($sellerProduct);

        /** @var SellerProduct $sellerProductUpdated */
        $sellerProductUpdated = $this->service->execute($sellerProduct->sellerProductId()->id(), 25, 25);

        $events = $sellerProduct->unCommittedEvents();

        $this->assertEquals(25, $sellerProductUpdated->stock()->value());
        $this->assertEquals(25, $sellerProductUpdated->amount()->value());

        $this->assertInstanceOf(StockWasChanged::class, array_pop($events));
        $this->assertNotInstanceOf(AmountWasChanged::class, array_pop($events));
    }

    /**
     * @test
     */
    public function whenUpdateAProductThatNotExistInInventoryThenThroesException()
    {
        $this->sellerProductRepository
            ->method('withId')
            ->will($this->throwException(new SellerProductNotExist()));

        $this->expectException(SellerProductNotExist::class);
        $this->service->execute(Uuid::uuid4()->toString(), 25, 25);
    }
}