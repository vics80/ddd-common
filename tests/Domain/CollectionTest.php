<?php
namespace Tests\Torvic\Common;

use Torvic\Common\Domain\CollectionTestClass;
use Torvic\Common\Domain\Exception\CollectionItemNotFoundException;
use Torvic\Common\Domain\Model\EntityTestClass;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{

    private $collection;
    private $entity;

    protected function setUp()
    {
        parent::setUp();
        $this->collection = new CollectionTestClass();
        $this->entity = new EntityTestClass();
    }

    /**
     * @test
     */
    public function keyMethodMustReturnZeroInNewCollection()
    {
        $this->assertSame(0, $this->collection->key());
    }

    /**
     * @test
     * @expectedException Torvic\Common\Domain\Exception\CollectionItemNotFoundException
     */
    public function currentMethodMustThrowExceptionIfNonExistenItemInCurrentPosition()
    {
        $this->collection->current();
    }

    /**
     * @test
     */
    public function currentMethodMustReturnValueInCurrentPosition()
    {
        $this->collection->add($this->entity);
        $this->assertSame($this->entity, $this->collection->current());
    }

    /**
     * @test
     */
    public function keyMethodMustReturn1AfterCallingNextMethod()
    {
        $this->collection->next();
        $this->assertSame(1, $this->collection->key());
    }

    /**
     * @test
     */
    public function validMethodMustReturnFalseIfCurrentItemDoesntExist()
    {
        $this->assertFalse($this->collection->valid());
    }

    /**
     * @test
     */
    public function validMethodMustReturnTrueIfCurrentItemExists()
    {
        $this->collection->add($this->entity);
        $this->assertTrue($this->collection->valid());
    }

    /**
     * @test
     */
    public function keyMethodMustReturZeroAfterCallingRewindInAnNonEmptyCollection()
    {
        $this->collection->add($this->entity);
        $this->collection->next();
        $this->collection->rewind();
        $this->assertSame(0, $this->collection->key());
    }

    /**
     * @test
     */
    public function toArrayMethodMustReturnArrayWithTheSameNumberOfItems()
    {
        $this->collection->add($this->entity);
        $this->assertTrue(is_array($this->collection->toArray()));
        $this->assertEquals($this->collection->count(), count($this->collection->toArray()));
    }

    /**
     * @test
     */
    public function addMethodMustAddObjectInLastPosition()
    {
        $this->collection->add($this->entity);
        $this->assertSame($this->entity, $this->collection->current());
    }

    /**
     * @test
     */
    public function addMethodMustIncrementsCount()
    {
        $countBefore = $this->collection->count();
        $this->collection->add($this->entity);
        $this->assertSame($countBefore + 1, $this->collection->count());
    }

    /**
     * @test
     */
    public function isEmptyMethodMustReturnFalseInACollectionWithOneOrMoreItems()
    {
        $this->collection->add($this->entity);
        $this->assertFalse($this->collection->isEmpty());
    }

    /**
     * @test
     */
    public function isEmptyMethodMustReturnTrueInACollectionWithoutItems()
    {
        $this->assertTrue($this->collection->isEmpty());
    }

    /**
     * @test
     */
    public function isEmptyMethodMustReturnTrueAfterCallingRemoveAllMethodInACollectionWithOneOrMoreItems()
    {
        $this->collection->add($this->entity);
        $this->collection->removeAll();
        $this->assertTrue($this->collection->isEmpty());
    }

    /**
     * @test
     */

    public function removeMethodMustDecreaseCount()
    {
        $this->collection->add($this->entity);
        $countBefore = $this->collection->count();
        $this->collection->remove(0);
        $this->assertSame($countBefore - 1, $this->collection->count());
    }

    /**
     * @test
     */
    public function removeMethodMustBeReorderedAfterRemovingOneItem()
    {
        $entity2 = new EntityTestClass();
        $entity3 = new EntityTestClass();
        $this->collection->add($this->entity);
        $this->collection->add($entity2);
        $this->collection->add($entity3);
        $this->collection->remove(1);
        $this->assertSame($this->entity, $this->collection->current());
        $this->collection->next();
        $this->assertNotSame($entity2, $this->collection->current());
        $this->assertSame($entity3, $this->collection->current());
    }
}
