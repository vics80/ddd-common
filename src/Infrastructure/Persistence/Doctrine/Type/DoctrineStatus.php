<?php
/**
 * Created by PhpStorm.
 * User: vigarcia
 * Date: 5/10/18
 * Time: 11:39
 */

namespace Torvic\Common\Infrastructure\Persistence\Doctrine\Type;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;

class DoctrineStatus extends IntegerType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->status();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $className = $this->getNamespace().'\\'.$this->getName();

        return new $className($value);
    }
}