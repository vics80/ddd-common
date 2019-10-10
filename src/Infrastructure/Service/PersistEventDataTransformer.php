<?php

namespace Torvic\Common\Infrastructure\Service;

use Torvic\Common\Domain\Event\PublishableDomainEvent;
use Torvic\Common\Domain\Model\Event\Event;

class PersistEventDataTransformer implements EventDataTransformer
{
    public function transform(PublishableDomainEvent $event)
    {
        if (!$event instanceof Event) {
            throw new \InvalidArgumentException("event is cannot be persist");
        }
        $transformedEvent = new Event();
        $transformedEvent->setType($event->type());
        $transformedEvent->setOccurredOn($event->occurredOn());
        $transformedEvent->setUserId($event->userId() ?? 0);
        $transformedEvent->setName($event->name());
        try {
            $eventContent = json_encode($event->toArray());
        } catch (\Throwable $error) {
            $eventContent = "event is not serializable";
        }

        $transformedEvent->setEventContent($eventContent);

        return $transformedEvent;

    }
}