<?php


namespace App\Catalog\Infrastructure\Persistence\Repository;


use App\Catalog\Domain\Model\Exception\ProductNotExist;
use App\Catalog\Domain\Model\Product;
use App\Catalog\Domain\Model\ProductId;
use App\Catalog\Domain\Model\ProductRepository;
use App\Shared\Domain\Model\Event;
use App\Shared\Infrastructure\Persistence\Repository\BaseRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DoctrineProductRepository extends BaseRepository implements ProductRepository
{
    /**
     * The event dispatcher can be replaced by messaging system for public messages to queues
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(ManagerRegistry $registry, EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($registry, Product::class);
        $this->registry = $registry;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function withId(ProductId $productId): Product
    {
        /** @var Product $product */
        $product = $this->find($productId);
        if (!$product) {
            throw new ProductNotExist();
        }

        return $product;
    }

    public function paginatedProducts(?int $offset = 0): array
    {
        $offset = $offset ?? 0;
        $query = $this
            ->getEntityManager()
            ->createQuery("SELECT P FROM App\Catalog\Domain\Model\Product P")
            ->setMaxResults(10)
            ->setFirstResult($offset ?? 0);

        $paginator = new Paginator($query, false);

        $nextOffset = ($offset) ? $offset + 10 : 10;

        return [
            "total" => $paginator->count(),
            "records" => $paginator->getIterator()->getArrayCopy(),
            "next_offset" => $nextOffset > $paginator->count() ? -1 : $nextOffset,
        ];
    }

    protected function dispatchEvent(Event $event, string $id)
    {
        $this->eventDispatcher->dispatch($event, $id);
    }
}