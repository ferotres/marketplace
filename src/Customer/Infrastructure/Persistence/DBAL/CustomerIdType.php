<?php


namespace App\Customer\Infrastructure\Persistence\DBAL;


use App\Customer\Domain\Model\CustomerId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Doctrine\DBAL\Types\Type;

class CustomerIdType extends GuidType
{
    const CUSTOMER_ID = Type::GUID;

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getGuidTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof CustomerId) {
            return $value->id();
        }

        return null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return CustomerId::create($value);
    }

    public function getName()
    {
        return self::CUSTOMER_ID;
    }
}