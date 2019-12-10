<?php


namespace App\Shared\Infrastructure\Persistence\DBAL;


use App\Shared\Domain\Model\Amount;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\Type;

class AmountType extends FloatType
{
    const AMOUNT = Type::FLOAT;

    /**
     * @param array            $fieldDeclaration
     * @param AbstractPlatform $platform
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getFloatDeclarationSQL($fieldDeclaration);
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof Amount) {
            return $value->value();
        }

        return null;
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     * @return Amount|mixed
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return Amount::create($value);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::AMOUNT;
    }
}