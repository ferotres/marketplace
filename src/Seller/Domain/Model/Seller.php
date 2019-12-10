<?php

namespace App\Seller\Domain\Model;

use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\Model\Email;

class Seller extends AggregateRoot
{
    /** @var SellerId */
    private $sellerId;
    /** @var string */
    private $name;
    /** @var Email */
    private $email;

    private function __construct(SellerId $sellerId, string $name, Email $email)
    {
        $this->sellerId = $sellerId;
        $this->name = $name;
        $this->email = $email;
        $this->addEvent(SellerWasCreated::create($sellerId));
    }

    public static function create(SellerId $sellerId, string $name, Email $email): Seller
    {
        return new self($sellerId, $name, $email);
    }

    public function change(string $name, Email $email): Seller
    {
        $this->name = $name;
        $this->email = $email;
        $this->addEvent(SellerWasUpdated::create($this->sellerId()));

        return $this;
    }

    public function delete()
    {
        $this->addEvent(SellerWasDeleted::create($this->sellerId()));
    }

    /**
     * @return SellerId
     */
    public function sellerId(): SellerId
    {
        return $this->sellerId;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return Email
     */
    public function email(): Email
    {
        return $this->email;
    }
}