<?php


namespace Pfilsx\DataGrid\tests\filters;


use Pfilsx\DataGrid\Grid\Filters\CustomFilter;
use Pfilsx\DataGrid\tests\BaseCase;

/**
 * Class CustomFilterTest
 * @package Pfilsx\DataGrid\tests\filters
 *
 * @property CustomFilter $testFilter
 */
class CustomFilterTest extends BaseCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->testFilter = new CustomFilter($this->container, [
            'value' => function($attr, $val){
                return 'test_filter';
            }
        ]);
    }

    public function testBlockName():void {
        $this->assertNull($this->testFilter->getBlockName());
    }

    public function testRender(): void
    {
        $renderResult = $this->testFilter->render('testAttribute', '1');
        $this->assertEquals('test_filter', $renderResult);
    }
}