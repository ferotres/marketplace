<?php


namespace App\Tests\UnitTest\Seller\Application\Service;

use App\Seller\Application\Service\RemoveProductOfSeller;
use App\Seller\Domain\Model\Exception\SellerProductAlreadyExist;
use App\Seller\Domain\Model\Exception\SellerProductNotExist;
use App\Seller\Domain\Model\SellerProductRepository;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class RemoveProductOfSellerTest extends TestCase
{

    private $sellerProductRepository;
    private $service;

    protected function setUp()
    {
        $this->sellerProductRepository = $this->createMock(SellerProductRepository::class);
        $this->service = new RemoveProductOfSeller($this->sellerProductRepository);
    }

    /**
     * @test
     */
    public function whenRemoveAProductOfInventoryThenReturnTrue()
    {
        $success = $this->service->execute(Uuid::uuid4()->toString());
        $this->assertTrue($success);
    }

    /**
     * @test
     */
    public function whenRemoveAProductOfInventoryThatNotExistThenThroesException()
    {
        $this->sellerProductRepository
            ->method('withId')
            ->will($this->throwException(new SellerProductNotExist()));

        $this->expectException(SellerProductNotExist::class);
        $this->service->execute(Uuid::uuid4()->toString());
    }

}