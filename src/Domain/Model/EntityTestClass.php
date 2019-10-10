<?php
namespace Torvic\Common\Domain\Model;


class EntityTestClass extends Entity
{
    public function __construct()
    {
        $this->setId(new Id('test_id'));
        parent::__construct();
    }
}