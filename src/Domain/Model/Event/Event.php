<?php

namespace Torvic\Common\Domain\Model\Event;

use Torvic\Common\Domain\Event\DomainEvent;
use Torvic\Common\Domain\Model\Id;
use Torvic\Common\Domain\Serializable;

class Event extends Serializable implements DomainEvent
{
    protected $id;
    protected $type = Event::class;
    protected $name = '';
    protected $userId;
    protected $eventContent = '';
    protected $occurredOn;
    protected $createdAt;

    public function __construct(Id $userId = null)
    {
        $this->userId = $userId;
        $this->occurredOn = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function occurredOn(): \DateTime
    {
        return $this->occurredOn;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function userId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @param $event
     *
     * @return mixed
     */
    public function setEventContent($event)
    {
        return $this->eventContent = $event;
    }

    /**
     * @return mixed
     */
    public function createdAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function toArray()
    {
        return json_decode($this->jsonSerialize(), true);
    }

    public function serialize()
    {
        return $this->jsonSerialize();
    }

    /**
     * @param \DateTime $occurredOn
     */
    public function setOccurredOn(\DateTime $occurredOn)
    {
        $this->occurredOn = $occurredOn;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function eventContent()
    {
        return $this->eventContent;
    }

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->id;
    }
}
