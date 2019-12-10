<?php


namespace App\Customer\Application\Service;


use App\Customer\Domain\Model\CartId;
use App\Customer\Domain\Model\CartRepository;

class RemoveCart
{

    /** @var CartRepository */
    private $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function execute(string $cartId): bool
    {
        $cart = $this->cartRepository->withId(CartId::create($cartId));
        $this->cartRepository->remove($cart);
        return true;
    }
}