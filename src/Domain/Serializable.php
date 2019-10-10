<?php

namespace Torvic\Common\Domain;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;

abstract class Serializable implements \JsonSerializable
{

    public function jsonSerialize()
    {
        $serializer = SerializerBuilder::create()
            ->addMetadataDir(__DIR__.'/../../../../../src/B1B2/Infrastructure/Serializer/Jms')
            ->build();

        $jsonContent = $serializer->serialize(
            $this,
            'json',
            SerializationContext::create()
                ->enableMaxDepthChecks()
                ->setSerializeNull(true)
        );

        return $jsonContent;
    }

    /**
     * @return array|mixed
     */
    abstract public function toArray();
}