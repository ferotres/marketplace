<?php


namespace App\Seller\Infrastructure\Persistence\DBAL;

use App\Seller\Domain\Model\SellerId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Doctrine\DBAL\Types\Type;

class SellerIdType extends GuidType
{
    const SELLER_ID = Type::GUID;

    /*
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param mixed[] $fieldDeclaration The field declaration.
     * @param AbstractPlatform $platform The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getGuidTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof SellerId) {
            return $value->id();
        }

        return null;
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     * @return SellerId|mixed
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return SellerId::create($value);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::SELLER_ID;
    }
}