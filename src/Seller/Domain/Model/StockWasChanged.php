<?php


namespace App\Seller\Domain\Model;


use App\Shared\Domain\Model\Event;

final class StockWasChanged implements Event
{
    /** @var SellerProductId */
    private $sellerProductId;

    private function __construct(SellerProductId $sellerProductId)
    {
        $this->sellerProductId = $sellerProductId;
    }

    public static function create(SellerProductId $sellerProductId): StockWasChanged
    {
        return new self($sellerProductId);
    }

    public function sellerProductId(): SellerProductId
    {
        return $this->sellerProductId;
    }

    public function id(): string
    {
        return 'stock_was_changed';
    }
}