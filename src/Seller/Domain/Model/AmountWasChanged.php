<?php


namespace App\Seller\Domain\Model;

use App\Shared\Domain\Model\Event;

final class AmountWasChanged implements Event
{

    /** @var SellerProductId */
    private $sellerProductId;

    private function __construct(SellerProductId $sellerProductId)
    {
        $this->sellerProductId = $sellerProductId;
    }

    public static function create(SellerProductId $sellerProductId): AmountWasChanged
    {
        return new self($sellerProductId);
    }

    public function sellerProductId(): SellerProductId
    {
        return $this->sellerProductId;
    }

    public function id(): string
    {
        return 'amount_was_changed';
    }
}