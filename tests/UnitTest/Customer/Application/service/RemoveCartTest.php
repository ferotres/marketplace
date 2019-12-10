<?php


namespace App\Tests\UnitTest\Customer\Application\service;


use App\Customer\Application\Service\RemoveCart;
use App\Customer\Domain\Model\CartId;
use App\Customer\Domain\Model\CartRepository;
use PHPUnit\Framework\TestCase;

class RemoveCartTest extends TestCase
{
    private $cartRepository;
    private $service;

    protected function setUp()
    {
        $this->cartRepository = $this->createMock(CartRepository::class);
        $this->service = new RemoveCart($this->cartRepository);
    }

    /**
     * @test
     */
    public function whenRemoveACartthenReturnTrue()
    {
        $success = $this->service->execute(CartId::create()->id());
        $this->assertTrue($success);
    }
}