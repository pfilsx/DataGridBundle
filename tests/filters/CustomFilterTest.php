<?php


namespace Pfilsx\DataGrid\tests\filters;


use Pfilsx\DataGrid\Grid\Filters\CustomFilter;
use Pfilsx\tests\OrmTestCase;

/**
 * Class CustomFilterTest
 * @package Pfilsx\DataGrid\tests\filters
 *
 * @property CustomFilter $testFilter
 */
class CustomFilterTest extends OrmTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->testFilter = new CustomFilter($this->serviceContainer, [
            'value' => function () {
                return 'test_filter';
            },
            'template' => $this->template
        ]);
    }

    public function testBlockName(): void
    {
        $this->assertNull($this->testFilter->getBlockName());
    }

    public function testRender(): void
    {
        $this->assertEquals('test_filter', $this->testFilter->render('testAttribute', '1'));
    }
}
