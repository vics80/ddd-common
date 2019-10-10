<?php

namespace Torvic\Common\Infrastructure\Event;


use Torvic\Common\Domain\Event\EventProducer;

interface EventProducerFactory
{
    public function get($queueService): EventProducer;
}