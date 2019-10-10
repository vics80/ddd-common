<?php

namespace Torvic\Common\Domain\Model;

use Cocur\Slugify\Slugify;
use Torvic\Common\Domain\Serializable;

abstract class Entity extends Serializable
{
    /**
     * @var Id
     */
    protected $id;

    /**
     * @var \DateTimeImmutable
     */
    protected $createdAt;

    /**
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * @var \DateTimeImmutable
     */
    protected $deletedAt;

    protected $slugify;

    public function __construct()
    {
        $now = new \DateTimeImmutable();
        $this->setCreatedAt($now);
        $this->setUpdatedAt(
            (new \DateTime())->setTimestamp(
                $now->getTimestamp()
            )
        );
        $this->slugify = new Slugify();
    }


    /**
     * @return Id|null
     */
    public function id(): ?Id
    {
        return $this->id;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function updatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param Id $id
     */
    public function setId(Id $id)
    {
        $this->id = $id;
    }

    /**
     * @param \DateTime $createdAt
     */
    private function setCreatedAt(\DateTimeImmutable $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @throws \Exception
     */
    public function delete()
    {
        $this->deletedAt = new \DateTimeImmutable();
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function deletedAt():? \DateTimeImmutable
    {
        return $this->deletedAt;
    }

    /**
     * @return array|mixed
     */
    public function toArray()
    {
        return json_decode($this->jsonSerialize(), true);
    }

    public function getIdString()
    {
        $id = $this->id();
        return isset($id) ? is_int($id) ? $id : $this->id()->id() : "";
    }
    /**
     * @param $id
     * @throws Exception\InvalidDomainIdException
     */
    public function setIdString($id)
    {
        return $this->setId(new Id($id));
    }
}