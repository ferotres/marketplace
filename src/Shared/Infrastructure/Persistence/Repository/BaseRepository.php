<?php


namespace App\Shared\Infrastructure\Persistence\Repository;

use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\Model\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

abstract class BaseRepository extends ServiceEntityRepository implements \App\Shared\Domain\Model\BaseRepository
{

    abstract protected function dispatchEvent(Event $event, string $id);

    public function __construct(ManagerRegistry $registry, $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    public function save(AggregateRoot $aggregateRoot)
    {
        $this->getEntityManager()->persist($aggregateRoot);
        $this->flush();

        /** @var Event $event */
        foreach ($aggregateRoot->unCommittedEvents() as $event) {
            $this->dispatchEvent($event, $event->id());
        }
    }

    public function remove(AggregateRoot $aggregateRoot)
    {
        $this->getEntityManager()->remove($aggregateRoot);
        $aggregateRoot->delete();
        $this->flush();
        /** @var Event $event */
        foreach ($aggregateRoot->unCommittedEvents() as $event) {
            $this->dispatchEvent($event, $event->id());
        }
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}