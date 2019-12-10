<?php


namespace App\Customer\Infrastructure\Persistence\DBAL;


use App\Customer\Domain\Model\CartId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Doctrine\DBAL\Types\Type;

class CartIdType extends GuidType
{
    const CART_ID = Type::GUID;

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getGuidTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof CartId) {
            return $value->id();
        }

        return null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return CartId::create($value);
    }

    public function getName()
    {
        return self::CART_ID;
    }
}