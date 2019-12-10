<?php


namespace App\Seller\Infrastructure\Persistence\Repository;


use App\Seller\Domain\Model\Exception\SellerNotExist;
use App\Seller\Domain\Model\Seller;
use App\Seller\Domain\Model\SellerId;
use App\Seller\Domain\Model\SellerRepository;
use App\Shared\Domain\Model\Email;
use App\Shared\Domain\Model\Event;
use App\Shared\Infrastructure\Persistence\Repository\BaseRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DoctrineSellerRepository extends BaseRepository implements SellerRepository
{
    /**
     * The event dispatcher can be replaced by messaging system for public messages to queues
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    public function __construct(ManagerRegistry $registry, EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($registry, Seller::class);
        $this->registry = $registry;
        $this->eventDispatcher = $eventDispatcher;
    }


    public function withId(SellerId $sellerId): Seller
    {
        /** @var Seller $seller */
        $seller = $this->find($sellerId);
        if (!$seller) {
            throw new SellerNotExist();
        }

        return $seller;
    }

    public function withEmail(Email $email): ?Seller
    {
        return $this->findOneBy(["email" => $email]);
    }

    protected function dispatchEvent(Event $event, string $id)
    {
        $this->eventDispatcher->dispatch($event, $id);
    }
}