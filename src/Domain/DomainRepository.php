<?php

namespace Torvic\Common\Domain;

use Torvic\Common\Domain\Model\Entity;
use Torvic\Common\Domain\Model\Id;

interface DomainRepository
{
    public function beginTransaction();

    public function rollback();

    public function findOrFail(Id $id): Entity;
}