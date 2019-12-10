<?php


namespace App\Seller\Infrastructure\Persistence\DBAL;

use App\Seller\Domain\Model\SellerProductId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Doctrine\DBAL\Types\Type;

class SellerProductIdType extends GuidType
{
    const SELLER_PRODUCT_ID = Type::GUID;

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
        if ($value instanceof SellerProductId) {
            return $value->id();
        }

        return null;
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     * @return SellerProductId|mixed
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return SellerProductId::create($value);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::SELLER_PRODUCT_ID;
    }
}