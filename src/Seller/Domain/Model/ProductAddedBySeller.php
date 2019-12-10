<?php


namespace App\Seller\Domain\Model;


use App\Shared\Domain\Model\Event;

final class ProductAddedBySeller implements Event
{

    /** @var SellerProductId */
    private $sellerProductId;

    private function __construct(SellerProductId $sellerProductId)
    {
        $this->sellerProductId = $sellerProductId;
    }

    public static function create(SellerProductId $sellerProductId): ProductAddedBySeller
    {
        return new self($sellerProductId);
    }

    public function sellerProductId(): SellerProductId
    {
        return $this->sellerProductId;
    }

    public function id(): string
    {
        return 'product_added_by_seller';
    }
}