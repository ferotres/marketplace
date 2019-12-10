<?php


namespace App\Seller\Infrastructure\Persistence\Repository;


use App\Catalog\Domain\Model\ProductId;
use App\Customer\Domain\Model\Exception\ProductNotAvailable;
use App\Seller\Domain\Model\Exception\SellerProductAlreadyExist;
use App\Seller\Domain\Model\Exception\SellerProductNotExist;
use App\Seller\Domain\Model\SellerId;
use App\Seller\Domain\Model\SellerProduct;
use App\Seller\Domain\Model\SellerProductId;
use App\Seller\Domain\Model\SellerProductRepository;
use App\Shared\Domain\Model\Event;
use App\Shared\Infrastructure\Persistence\Repository\BaseRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DoctrineSellerProductRepository extends BaseRepository implements SellerProductRepository
{
    /**
     * The event dispatcher can be replaced by messaging system for public messages to queues
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    public function __construct(ManagerRegistry $registry, EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($registry, SellerProduct::class);
        $this->eventDispatcher = $eventDispatcher;
    }

    public function withId(SellerProductId $sellerProductId): SellerProduct
    {
        /** @var SellerProduct $sellerProduct */
        $sellerProduct = $this->find($sellerProductId);
        if (!$sellerProduct) {
            throw new SellerProductNotExist();
        }

        return $sellerProduct;
    }

    public function sellerProductExist(SellerId $sellerId, ProductId $productId)
    {
        /** @var SellerProduct $sellerProduct */
        $sellerProduct = $this->findOneBy(["sellerId" => $sellerId, "productId" => $productId]);
        if ($sellerProduct) {
            throw new SellerProductAlreadyExist();
        }
    }

    protected function dispatchEvent(Event $event, string $id)
    {
        $this->eventDispatcher->dispatch($event, $id);
    }

    public function bestSellerForAProduct(ProductId $productId, int $quantity): SellerProduct
    {
        $query = $this
            ->getEntityManager()
            ->createQuery(
                "
                SELECT SP FROM App\Seller\Domain\Model\SellerProduct SP
                    WHERE SP.productId = :productId
                    AND SP.stock >= :quantity
                    ORDER BY SP.amount ASC"
            )
            ->setParameter('productId', $productId)
            ->setParameter('quantity', $quantity);

        $sellerProduct = $query->getResult();

        if (count($sellerProduct) === 0) {
            throw new ProductNotAvailable();
        }

        return array_shift($sellerProduct);
    }

    public function availableProductForCommit(SellerProductId $sellerProductId, int $quantity): SellerProduct
    {
        $query = $this
            ->getEntityManager()
            ->createQuery(
                "
                SELECT SP FROM App\Seller\Domain\Model\SellerProduct SP
                    WHERE SP.sellerProductId = :sellerProductId
                    AND SP.stock >= :quantity
                    "
            )
            ->setParameter('sellerProductId', $sellerProductId)
            ->setParameter('quantity', $quantity);

        $sellerProduct = $query->getOneOrNullResult();

        if (!$sellerProduct) {
            throw new ProductNotAvailable(
                sprintf("Product of seller with id %S not Available", $sellerProductId->id())
            );
        }

        return $sellerProduct;
    }
}