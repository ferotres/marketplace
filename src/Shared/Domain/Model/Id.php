<?php


namespace App\Shared\Domain\Model;


use Assert\Assertion;
use Ramsey\Uuid\Uuid;

abstract class Id
{
    /** @var string */
    private $id;

    public function __construct(string $id = null)
    {
        Assertion::nullOrUuid($id);
        $this->id = $id ?? Uuid::uuid4()->toString();
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function equalTo(Id $id) :bool
    {
        return $this->id === $id->id();
    }
}