<?php

namespace Torvic\Common\Infrastructure\Persistence\Redis\Repository;


use Torvic\Common\Domain\DomainRepository;

class RedisRepository
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new \Redis();
        $this->repository->connect($_ENV['REDIS_URL']);
    }

    public function find($key)
    {
        return $this->repository->get($key);
    }

    public function persist($key, $value)
    {
        $this->repository->set($key, $value);
    }
}