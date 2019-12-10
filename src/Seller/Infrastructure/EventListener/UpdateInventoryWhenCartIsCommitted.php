<?php


namespace App\Seller\Infrastructure\EventListener;


use App\Customer\Domain\Model\CartItem;
use App\Customer\Domain\Model\CartRepository;
use App\Customer\Domain\Model\CartWasCommitted;
use App\Seller\Application\Service\UpdateInventoryOfSeller;

class UpdateInventoryWhenCartIsCommitted
{
    /** @var UpdateInventoryOfSeller */
    private $updateInventoryOfSeller;
    /** @var CartRepository */
    private $cartRepository;

    public function __construct(UpdateInventoryOfSeller $updateInventoryOfSeller, CartRepository $cartRepository)
    {
        $this->updateInventoryOfSeller = $updateInventoryOfSeller;
        $this->cartRepository = $cartRepository;
    }

    public function onBind(CartWasCommitted $event)
    {
        try {
            $cart = $this->cartRepository->withId($event->cartId());
            /** @var CartItem $cartItem */
            foreach ($cart->cartItems() as $cartItem) {
                $this->updateInventoryOfSeller->execute($cartItem->sellerProductId(), $cartItem->quantity());
            }

        } catch (\Throwable $exception) {
            // TODO: Notify failure to post-sales, wrong delivery is coming
        }

        return true;
    }
}