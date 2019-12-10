<?php


namespace App\Tests\UnitTest\Seller\Application\Service;


use App\Seller\Application\Service\DeleteSeller;
use App\Seller\Domain\Model\Exception\SellerNotExist;
use App\Seller\Domain\Model\Seller;
use App\Seller\Domain\Model\SellerId;
use App\Seller\Domain\Model\SellerRepository;
use App\Shared\Domain\Model\Email;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class DeleteSellerTest extends TestCase
{

    private $sellerRepository;
    private $service;

    protected function setUp()
    {
        $this->sellerRepository = $this->createMock(SellerRepository::class);
        $this->service = new DeleteSeller($this->sellerRepository);
    }

    /**
     * @test
     */
    public function whenDeleteASellerThatNotExistThenTrowAExcetion()
    {
        $this->sellerRepository
            ->method('withId')
            ->will($this->throwException(new SellerNotExist()));

        $this->expectException(SellerNotExist::class);
        $this->service->execute(Uuid::uuid4()->toString());
    }

    /**
     * @test
     */
    public function whenDeleteASellerThenReturnTrue()
    {
        $seller = Seller::create(SellerId::create(), 'c', Email::create('test@test.com'));
        $this->sellerRepository
            ->method('withId')
            ->willReturn($seller);

        $success = $this->service->execute(Uuid::uuid4()->toString());

        $this->assertTrue($success);
    }

}