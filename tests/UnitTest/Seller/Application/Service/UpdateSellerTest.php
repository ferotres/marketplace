<?php


namespace App\Tests\UnitTest\Seller\Application\Service;


use App\Seller\Application\Service\UpdateSeller;
use App\Seller\Domain\Model\Exception\SellerNotExist;
use App\Seller\Domain\Model\Seller;
use App\Seller\Domain\Model\SellerId;
use App\Seller\Domain\Model\SellerRepository;
use App\Seller\Domain\Model\SellerWasUpdated;
use App\Shared\Domain\Model\Email;
use Assert\AssertionFailedException;
use http\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UpdateSellerTest extends TestCase
{
    private $sellerRepository;
    private $service;

    protected function setUp()
    {
        $this->sellerRepository = $this->createMock(SellerRepository::class);
        $this->service = new UpdateSeller( $this->sellerRepository);
    }

    /**
     * @test
     */
    public function whenSellerNotExistThenTrownAException()
    {
        $this->sellerRepository
            ->method('withId')
            ->will($this->throwException(new SellerNotExist()));

        $this->expectException(SellerNotExist::class);
        $this->service->execute(Uuid::uuid4()->toString(), 'c', 'test@test.com');

    }

    /**
     * @test
     */
    public function whenUpdateSellerThenReturnSellerInstanceUpdated()
    {
        $seller = Seller::create(SellerId::create(), 'c', Email::create('test@test.com'));
        $this->sellerRepository
            ->method('withId')
            ->willReturn($seller);

        /** @var Seller $sellerUpdated */
        $sellerUpdated = $this->service->execute(Uuid::uuid4()->toString(), 'a', 'test2@test.com');

        $events = $sellerUpdated->unCommittedEvents();
        $this->assertInstanceOf(Seller::class, $seller);
        $this->assertInstanceOf(SellerWasUpdated::class, array_pop($events));
        $this->assertEquals('a', $sellerUpdated->name());
        $this->assertEquals('test2@test.com', $sellerUpdated->email());

        return $seller;
    }

    /**
     * @test
     * @depends whenUpdateSellerThenReturnSellerInstanceUpdated
     */
    public function whenUpdateSellerWithWrongEmailThenTrownAException(Seller $seller)
    {
        $this->sellerRepository
            ->method('withId')
            ->willReturn($seller);

        $this->expectException(AssertionFailedException::class);
        $this->service->execute(Uuid::uuid4()->toString(), 'c', 'test.com');

    }

}