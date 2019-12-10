<?php


namespace App\Seller\Domain\Model;


use App\Shared\Domain\Model\Event;

final class SellerWasDeleted implements Event
{

    /** @var SellerId */
    private $sellerId;

    private function __construct(SellerId $sellerId)
    {
        $this->sellerId = $sellerId;
    }

    public static function create(SellerId $sellerId): SellerWasDeleted
    {
        return new self($sellerId);
    }

    public function sellerId(): SellerId
    {
        return $this->sellerId;
    }

    public function id(): string
    {
        return 'seller_was_deleted';
    }
}