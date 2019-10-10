<?php

namespace Torvic\Common\Domain\Event;


interface DomainEvent
{
    /**
     * @return \DateTime
     */
    public function occurredOn();
}