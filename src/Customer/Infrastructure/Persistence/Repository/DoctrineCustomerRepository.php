<?php


namespace App\Customer\Infrastructure\Persistence\Repository;

use App\Customer\Domain\Model\Customer;
use App\Customer\Domain\Model\CustomerId;
use App\Customer\Domain\Model\CustomerRepository;
use App\Customer\Domain\Model\Exception\CustomerNotExist;
use App\Shared\Domain\Model\Event;
use App\Shared\Infrastructure\Persistence\Repository\BaseRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DoctrineCustomerRepository extends BaseRepository implements CustomerRepository
{

    /**
     * The event dispatcher can be replaced by messaging system for public messages to queues
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(ManagerRegistry $registry, EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($registry, Customer::class);
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function dispatchEvent(Event $event, string $id)
    {
        return;
    }

    public function withId(CustomerId $customerId): Customer
    {
        /** @var Customer $customer */
        $customer = $this->find($customerId);
        if (!$customer) {
            throw new CustomerNotExist();
        }

        return $customer;
    }
}