<?php


namespace App\Customer\Infrastructure\Persistence\Repository;


use App\Customer\Domain\Model\Cart;
use App\Customer\Domain\Model\CartId;
use App\Customer\Domain\Model\CartItem;
use App\Customer\Domain\Model\CartItemId;
use App\Customer\Domain\Model\CartRepository;
use App\Customer\Domain\Model\CustomerId;
use App\Customer\Domain\Model\Exception\CartNotExist;
use App\Customer\Domain\Model\Exception\CustomerAlreadyHasACartUncommitted;
use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\Model\Event;
use App\Shared\Infrastructure\Persistence\Repository\BaseRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DoctrineCartRepository extends BaseRepository implements CartRepository
{

    /**
     * The event dispatcher can be replaced by messaging system for public messages to queues
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /** @var EntityRepository */
    private $cartItemRepository;

    public function __construct(ManagerRegistry $registry, EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($registry, Cart::class);
        $this->eventDispatcher = $eventDispatcher;
        $this->cartItemRepository = $this
            ->getEntityManager()
            ->getRepository('App\Customer\Domain\Model\CartItem');
    }

    protected function dispatchEvent(Event $event, string $id)
    {
        $this->eventDispatcher->dispatch($event, $id);
    }

    public function withId(CartId $cartId): Cart
    {
        /** @var Cart $cart */
        $cart = $this->find($cartId);
        if (!$cart) {
            throw new CartNotExist();
        }

        $cart->setCardItems($this->cartItemRepository->findBy(["cartId" => $cartId]));

        return $cart;
    }

    public function customerAlreadyHasACart(CustomerId $customerId)
    {
        $cart = $this->findOneBy(['customerId' => $customerId, "committed" => false]);
        if ($cart) {
            throw new CustomerAlreadyHasACartUncommitted();
        }
    }

    public function save(AggregateRoot $aggregateRoot)
    {
        /** @var Cart $cart */
        $cart = $aggregateRoot;

        $this->getEntityManager()->persist($cart);

        /** @var CartItem $cartItem */
        foreach ($cart->cartItems() as $cartItem) {

            $this->cartItemRepository->getEntityManager()->persist($cartItem);
        }

        $this->getEntityManager()->flush();

        /** @var Event $event */
        foreach ($cart->unCommittedEvents() as $event) {
            $this->dispatchEvent($event, $event->id());
        }
    }

    public function remove(AggregateRoot $aggregateRoot)
    {
        /** @var Cart $cart */
        $cart = $aggregateRoot;

        /** @var CartItem $cartItem */
        foreach ($cart->cartItems() as $cartItem) {

            $this->cartItemRepository->getEntityManager()->remove($cartItem);
        }

        $this->getEntityManager()->remove($cart);
        $this->getEntityManager()->flush();

        $aggregateRoot->delete();

        /** @var Event $event */
        foreach ($cart->unCommittedEvents() as $event) {
            $this->dispatchEvent($event, $event->id());
        }
    }

    public function removeCartItem(CartId $cartId, CartItemId $cartItemId)
    {
        $this->cartItemRepository
            ->getEntityManager()
            ->createQuery(
                "
                DELETE FROM App\Customer\Domain\Model\CartItem CI 
                    WHERE CI.cartItemId = :cartItemId 
                    AND CI.cartId = :cartId
             "
            )
            ->setParameter('cartItemId', $cartItemId)
            ->setParameter('cartId', $cartId)
            ->getResult();
    }
}