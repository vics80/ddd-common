<?php

namespace Torvic\Common\Domain;

interface DomainRepository
{
    public function beginTransaction();

    public function rollback();
}