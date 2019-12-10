<?php


namespace App\Customer\Domain\Model;


use App\Shared\Domain\Model\Email;

class Customer
{
    /** @var CustomerId */
    private $customerId;
    /** @var Email */
    private $email;

    private function __construct(CustomerId $customerId, Email $email)
    {
        $this->customerId = $customerId;
        $this->email = $email;
    }

    public static function create(CustomerId $customerId, Email $email): Customer
    {
        return new self($customerId, $email);
    }

    public function customerId(): CustomerId
    {
        return $this->customerId;
    }

    public function email(): Email
    {
        return $this->email;
    }


}