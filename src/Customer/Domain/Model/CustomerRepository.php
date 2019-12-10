<?php


namespace App\Customer\Domain\Model;


interface CustomerRepository
{
    public function withId(CustomerId $customerId): Customer;
}