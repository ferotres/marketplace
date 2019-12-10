<?php


namespace App\Tests\UnitTest\Customer\Application\service;


use App\Customer\Application\Service\CreateCart;
use App\Customer\Domain\Model\Cart;
use App\Customer\Domain\Model\CartRepository;
use App\Customer\Domain\Model\Customer;
use App\Customer\Domain\Model\CustomerId;
use App\Customer\Domain\Model\CustomerRepository;
use App\Shared\Domain\Model\Email;
use PHPUnit\Framework\TestCase;

class CreateCartTest extends TestCase
{
    private $cartRepository;
    private $customerReposiotry;
    private $service;

    protected function setUp()
    {
        $this->cartRepository = $this->createMock(CartRepository::class);
        $this->customerReposiotry = $this->createMock(CustomerRepository::class);
        $this->service = new CreateCart($this->cartRepository, $this->customerReposiotry);
    }

    /**
     * @test
     * @return Customer
     */
    public function createACostumer() :Customer
    {
        $customer =  Customer::create(
            CustomerId::create('58952145-1a8f-11ea-b51d-0242ac140002'),
            Email::create("test@test.com")
        );

        $this->assertInstanceOf(Customer::class, $customer);

        return $customer;
    }

    /**
     * @test
     * @depends createACostumer
     */
    public function whenCreateCartthenReturnInstance(Customer $customer)
    {
        $this->customerReposiotry
            ->method('withId')
            ->willReturn($customer);

        $cart = $this->service->execute($customer->customerId()->id());

        $this->assertInstanceOf(Cart::class, $cart);
    }
}