<?php


namespace App\Shared\Infrastructure\Persistence\DBAL;


use App\Shared\Domain\Model\Email;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Types\Type;

class EmailType extends StringType
{
    const EMAIL = Type::STRING;

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
        return 'VARCHAR(150)';
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof Email) {
            return $value->value();
        }

        return null;
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     * @return Email|mixed
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return Email::create($value);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::EMAIL;
    }
}