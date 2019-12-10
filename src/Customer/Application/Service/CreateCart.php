<?php


namespace App\Customer\Application\Service;


use App\Customer\Domain\Model\Cart;
use App\Customer\Domain\Model\CartId;
use App\Customer\Domain\Model\CartRepository;
use App\Customer\Domain\Model\CustomerId;
use App\Customer\Domain\Model\CustomerRepository;

class CreateCart
{
    /** @var CartRepository */
    private $cartRepository;
    /** @var CustomerRepository */
    private $customerRepository;

    public function __construct(CartRepository $cartRepository, CustomerRepository $customerRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->customerRepository = $customerRepository;
    }

    public function execute(string $customerId): Cart
    {
        $customerId = CustomerId::create($customerId);
        $this->cartRepository->customerAlreadyHasACart($customerId);
        $customer = $this->customerRepository->withId($customerId);

        $cart = Cart::create(CartId::create(), $customer->customerId());

        $this->cartRepository->save($cart);

        return $cart;
    }
}