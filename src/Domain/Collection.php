<?php

namespace Torvic\Common\Domain;

use Torvic\Common\Domain\Exception\CollectionItemNotFoundException;

abstract class Collection extends Serializable implements \Iterator, \Countable, \JsonSerializable
{

    /** @var array */
    protected $collection = [];

    /** @var int */
    protected $position = 0;

    /**
     * Collection constructor.
     *
     */
    public function __construct()
    {
        $this->position = 0;
    }

    /**
     * Return the current element
     *
     * @link  http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        if (!isset($this->collection[$this->position])) {
            throw new CollectionItemNotFoundException('Not More Items');
        }

        return $this->collection[$this->position];
    }

    /**
     * Move forward to next element
     *
     * @link  http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        ++ $this->position;
    }

    /**
     * Return the key of the current element
     *
     * @link  http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Checks if current position is valid
     *
     * @link  http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     *        Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid(): bool
    {
        return isset($this->collection[$this->position]);
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link  http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $collection = [];
        $totalItems = count($this->collection);
        for ($i = 0; $i < $totalItems; $i++) {
            $collection[] = $this->collection[$i]->toArray();
        }

        return $collection;
    }


    /**
     * @param $object
     *
     * @return void
     */
    public function add(Serializable $object)
    {
        $this->validateAdd($object);

        $this->collection[] = $object;
    }


    /**
     * @param int $key
     *
     * @return void
     */
    public function remove($key)
    {
        $this->validateRemove($key);

        unset($this->collection[$key]);
        $this->collection = array_values($this->collection);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->collection);
    }

    public function isEmpty()
    {
        return !$this->count();
    }

    abstract protected function validateAdd($object);

    abstract protected function validateRemove($key);

    public function removeAll()
    {
        $this->collection = [];
    }


}