<?php


namespace App\Shared\Domain\Model;

use Assert\Assertion;

final class Email
{
    /** @var string */
    private $email;

    public function __construct(string $email)
    {
        Assertion::email($email);
        $this->email = $email;
    }

    public static function create(string $email): Email
    {
        return new self($email);
    }

    public function change(string $email): Email
    {
        return new self($email);
    }

    public function value(): string
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}