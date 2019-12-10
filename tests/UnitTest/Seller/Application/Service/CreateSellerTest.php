<?php


namespace App\Tests\UnitTest\Seller\Application\Service;


use App\Seller\Application\Service\CreateSeller;
use App\Seller\Domain\Model\Exception\SellerAreadyExist;
use App\Seller\Domain\Model\Seller;
use App\Seller\Domain\Model\SellerId;
use App\Seller\Domain\Model\SellerRepository;
use App\Seller\Domain\Model\SellerWasCreated;
use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

class CreateSellerTest extends TestCase
{
    private $sellerRepository;
    private $service;

    protected function setUp()
    {
        $this->sellerRepository = $this->createMock(SellerRepository::class);
        $this->service = new CreateSeller($this->sellerRepository);
    }

    /**
     * @test
     */
    public function whenCreateASellerWithWrongEmailThenThrowException()
    {
        $this->expectException(AssertionFailedException::class);
        $this->service->execute('test', 'test.es');
    }

    /**
     * @test
     */
    public function whenCreateASellerThenReturnInstanceAndEmitEvents()
    {
        $this->sellerRepository
            ->method('withEmail')
            ->willReturn(null);

        /** @var Seller $seller */
        $seller = $this->service->execute('test', 'test@test.es');
        $events = $seller->unCommittedEvents();

        $this->assertInstanceOf(Seller::class, $seller);
        $this->assertInstanceOf(SellerWasCreated::class, array_pop($events));

        return $seller;
    }

    /**
     * @test
     * @depends whenCreateASellerThenReturnInstanceAndEmitEvents
     */
    public function whenCreateASellerThatAlreadyExistThenThrowException(Seller $seller)
    {
        $this->sellerRepository
            ->method('withEmail')
            ->willReturn($seller);

        $this->expectException(SellerAreadyExist::class);
        $this->service->execute('test', 'test@test.es');
    }


}