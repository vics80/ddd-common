<?php

namespace Torvic\Common\Domain\Model\Event;

use Torvic\Common\Domain\Event\PublishableDomainEvent;

interface EventRepository
{
    public function persist(PublishableDomainEvent $event);
}