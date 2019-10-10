<?php

namespace Torvic\Common\Infrastructure\Service;

use Torvic\Common\Domain\Event\PublishableDomainEvent;

interface EventDataTransformer
{
    public function transform(PublishableDomainEvent $event);
}