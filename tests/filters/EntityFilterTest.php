<?php


namespace Pfilsx\DataGrid\tests\filters;


use Pfilsx\DataGrid\Grid\Filters\EntityFilter;
use Pfilsx\tests\OrmTestCase;
use Pfilsx\tests\TestEntities\Node;

/**
 * Class EntityFilterTest
 * @package Pfilsx\DataGrid\tests\filters
 *
 * @property EntityFilter $testFilter
 */
class EntityFilterTest extends OrmTestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->testFilter = new EntityFilter($this->serviceContainer, [
            'label' => 'user',
            'entityClass' => Node::class,
            'template' => $this->template
        ]);
    }

    public function testGetLabel(): void
    {
        $this->assertEquals('user', $this->testFilter->getLabel());
    }

    public function testGetEntityClass(): void
    {
        $this->assertEquals(Node::class, $this->testFilter->getEntityClass());
    }

    public function testRender(): void
    {
        $this->assertEquals('select>option>1:joe option>2:toto option>3:toto option>4:toto option>5:toto option>6:foouser option>7:fös option>8:foouser option>9:foo option>10:bar option>11:toto', trim($this->testFilter->render('testAttribute', '1')));
    }
}
