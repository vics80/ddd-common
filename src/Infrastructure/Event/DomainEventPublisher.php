<?php

namespace Torvic\Common\Infrastructure\Event;


use Torvic\Common\Domain\Event\DomainEvent;
use Torvic\Common\Domain\Event\DomainEventSubscriber;
use Torvic\Common\Domain\Event\EventProducer;
use Torvic\Common\Domain\Event\EventPublisher;
use Torvic\Common\Domain\Exception\DomainEventPublishException;

class DomainEventPublisher implements EventPublisher
{
    /**
     * @var DomainEventSubscriber[]
     */
    private static $subscribers;

    /** @var  EventProducerFactory */
    private static $publisherFactory;

    private $id = 0;

    private static $queueService;

    /**
     * DomainEventPublisher constructor.
     * @param EventProducerFactory $eventProducerFactory
     * @param $queueService
     */
    public function __construct(
        EventProducerFactory $eventProducerFactory,
        $queueService
    )
    {
        self::$subscribers = [];
        self::$queueService = $queueService;
        self::$publisherFactory = $eventProducerFactory;
    }

    public function __clone()
    {
        throw new \BadMethodCallException('Clone is not supported');
    }

    /**
     * @param DomainEventSubscriber $aDomainEventSubscriber
     * @return int
     */
    public function subscribe(DomainEventSubscriber $aDomainEventSubscriber)
    {
        $id = $this->id;
        self::$subscribers[$id] = $aDomainEventSubscriber;
        $this->id++;
        return $id;
    }

    /**
     * @param $id
     * @return DomainEventSubscriber|mixed|null
     */
    public function ofId($id)
    {
        return isset(self::$subscribers[$id]) ? self::$subscribers[$id] : null;
    }

    /**
     * @param $id
     */
    public function unsubscribe($id)
    {
        unset(self::$subscribers[$id]);
    }

    /**
     * @param DomainEvent $aDomainEvent
     * @throws DomainEventPublishException
     */
    public function publish(DomainEvent $aDomainEvent)
    {
        try {
            $producer = self::$publisherFactory->get(self::$queueService);
            /**@var EventProducer $producer */
            $producer->add($aDomainEvent);
        } catch (\Throwable $error) {
            throw new DomainEventPublishException($error->getMessage());
        }
    }

    /**
     * @param DomainEvent $aDomainEvent
     */
    public function publishSync(DomainEvent $aDomainEvent)
    {
        $this->dispatch($aDomainEvent);
    }

    /**
     * @param DomainEvent $aDomainEvent
     */
    public function dispatch(DomainEvent $aDomainEvent)
    {
        foreach (self::$subscribers as $aSubscriber) {
            if ($aSubscriber->isSubscribedTo($aDomainEvent)) {
                $aSubscriber->handle($aDomainEvent);
            }
        }
    }

    public static function raise(DomainEvent $aDomainEvent)
    {
        foreach (self::$subscribers as $aSubscriber) {
            if ($aSubscriber->isSubscribedTo($aDomainEvent)) {
                $aSubscriber->handle($aDomainEvent);
            }
        }
    }

    /**
     * @param DomainEvent $aDomainEvent
     * @throws DomainEventPublishException
     */
    public static function raiseAsync(DomainEvent $aDomainEvent)
    {
        try {
            $producer = self::$publisherFactory->get(self::$queueService);
            /**@var EventProducer $producer */
            $producer->add($aDomainEvent);
        } catch (\Throwable $error) {
            throw new DomainEventPublishException($error->getMessage());
        }
    }
}