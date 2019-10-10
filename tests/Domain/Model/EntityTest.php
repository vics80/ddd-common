<?php
/**
 * Created by PhpStorm.
 * User: vigarcia
 * Date: 23/10/18
 * Time: 14:52
 */

namespace Tests\Torvic\Common\Domain\Model;

use Torvic\Common\Domain\Model\EntityTestClass;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{
    private $entity;

    protected function setUp()
    {
        parent::setUp();
        $this->entity = new EntityTestClass();
    }

    /**
     * @test
     */
    public function newEntityProperCreation()
    {
        $this->assertEquals('test_id', $this->entity->id());
        $this->assertNotNull($this->entity->createdAt());
        $this->assertNotNull($this->entity->updatedAt());
        $this->assertNotSame($this->entity->createdAt(), $this->entity->updatedAt());
        $this->assertEquals($this->entity->createdAt(), $this->entity->updatedAt());
    }
}
