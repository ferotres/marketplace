<?php


namespace App\Seller\Infrastructure\UI\DataTransformer;


use App\Seller\Domain\Model\Seller;

class SellerDataTransformer
{
    /** @var Seller */
    private $seller;

    private function __construct(Seller $seller)
    {
        $this->seller = $seller;
    }

    public static function write(Seller $seller): SellerDataTransformer
    {
        return new self($seller);
    }

    public function read(): array
    {
        return [
            "id" => $this->seller->sellerId()->id(),
            "name" => $this->seller->name(),
            "email" => $this->seller->email()->value(),
        ];
    }
}