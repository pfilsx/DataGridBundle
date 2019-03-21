<?php


namespace Pfilsx\DataGrid\tests\filters;


use Pfilsx\DataGrid\Grid\Filters\EntityFilter;
use Pfilsx\DataGrid\tests\BaseCase;

/**
 * Class EntityFilterTest
 * @package Pfilsx\DataGrid\tests\filters
 *
 * @property EntityFilter $testFilter
 */
class EntityFilterTest extends BaseCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->testFilter = new EntityFilter($this->container, [
            'label' => 'title',
            'entityClass' => 'App\Entity\TestEntity'
        ]);
    }

    public function testGetLabel(): void
    {
        $this->assertEquals('title', $this->testFilter->getLabel());
    }

    public function testGetEntityClass(): void
    {
        $this->assertEquals('App\Entity\TestEntity', $this->testFilter->getEntityClass());
    }

    public function testRender(): void
    {
        $renderResult = json_decode($this->testFilter->render('testAttribute', '1'), true);
        $this->assertEquals('grid_filter', $renderResult[0]);
    }
}