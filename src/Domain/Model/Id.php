<?php

namespace Torvic\Common\Domain\Model;

use Torvic\Common\Domain\Exception\InvalidDomainIdException;
use Torvic\Common\Domain\Serializable;

class Id extends Serializable
{
    protected $id;

    /**
     * Id constructor.
     * @param $id
     * @throws InvalidDomainIdException
     */
    public function __construct($id)
    {
        if (is_null($id)) {
            throw new InvalidDomainIdException("invalid id");
        }
        $this->id = $id;
    }

    /**
     * @return array|mixed
     */
    public function toArray()
    {
        return [
            'id' => $this->id(),
        ];
    }

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->id;
    }

    public function equals(Id $id)
    {
        return $id->id() == $this->id();
    }

    public function getIdString()
    {
        return $this->id() ?? null;
    }
}