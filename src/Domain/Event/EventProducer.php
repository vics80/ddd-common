<?php

namespace Torvic\Common\Domain\Event;


interface EventProducer
{
    public function add(DomainEvent $event);
}