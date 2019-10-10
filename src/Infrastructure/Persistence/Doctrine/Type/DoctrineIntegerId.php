<?php

namespace Torvic\Common\Infrastructure\Persistence\Doctrine\Type;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;

abstract class DoctrineIntegerId extends IntegerType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? $value->id() : null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $className = $this->getNamespace() . '\\' . $this->getName();
        return new $className($value);
    }

    public abstract function getNamespace();
}