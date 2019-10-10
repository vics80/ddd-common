<?php

namespace Tests\Torvic\Common\Domain\Model;

use Torvic\Common\Domain\Exception\InvalidDomainIdException;
use Torvic\Common\Domain\Model\Id;
use PHPUnit\Framework\TestCase;

class IdTest extends TestCase
{
    private $id;

    protected function setUp()
    {
        parent::setUp();
        $this->id = new Id('testId');
    }

    /**
     * @test
     * @expectedException Torvic\Common\Domain\Exception\InvalidDomainIdException
     */
    public function newIdMustThrowExceptionWithNullId()
    {
        new Id(null);
    }

    /**
     * @test
     */
    public function toArrayMethodMustReturnAssociativeArrayWithIdPositionAndProperValue()
    {
        $arrayId = $this->id->toArray();
        $this->assertEquals('testId', $arrayId['id']);
    }

    /**
     * @test
     */
    public function toStringMethodMustReturnStringWithId()
    {
        $this->assertEquals('testId', $this->id);
    }

    /**
     * @test
     */
    public function equalsMustReturnTrueComparingWithAnotherIdWithSameValue()
    {
        $this->assertTrue(
            $this->id->equals(
                new Id(
                    'testId'
                )
            )
        );
    }

    /**
     * @test
     */
    public function equalsMustReturnFalseComparingWithAnotherIdWithDiferentValue()
    {
        $this->assertFalse(
            $this->id->equals(
                new Id(
                    'anId'
                )
            )
        );
    }
}
