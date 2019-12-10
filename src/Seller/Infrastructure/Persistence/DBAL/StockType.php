<?php


namespace App\Seller\Infrastructure\Persistence\DBAL;


use App\Seller\Domain\Model\Stock;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\DBAL\Types\Type;

class StockType extends IntegerType
{
    const STOCK = Type::INTEGER;

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
        return $platform->getIntegerTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof Stock) {
            return $value->value();
        }

        return null;
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     * @return Stock|mixed
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return Stock::create($value);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::STOCK;
    }
}