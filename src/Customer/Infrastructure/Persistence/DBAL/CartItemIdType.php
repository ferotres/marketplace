<?php


namespace App\Customer\Infrastructure\Persistence\DBAL;


use App\Customer\Domain\Model\CartItemId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Doctrine\DBAL\Types\Type;

class CartItemIdType extends GuidType
{
    const CART_ITEM_ID = Type::GUID;

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getGuidTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof CartItemId) {
            return $value->id();
        }

        return null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return CartItemId::create($value);
    }

    public function getName()
    {
        return self::CART_ITEM_ID;
    }
}