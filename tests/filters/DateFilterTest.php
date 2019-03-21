<?php


namespace Pfilsx\DataGrid\tests\filters;


use Pfilsx\DataGrid\Grid\Filters\DateFilter;
use Pfilsx\DataGrid\tests\BaseCase;

/**
 * Class DateFilterTest
 * @package Pfilsx\DataGrid\tests\filters
 *
 * @property DateFilter $testFilter
 */
class DateFilterTest extends BaseCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->testFilter = new DateFilter($this->container, [
            'minDate' => '01-01-1990',
            'maxDate' => 'now'
        ]);
    }

    public function testGetMinDate(): void
    {
        $this->assertEquals('01-01-1990', $this->testFilter->getMinDate());
    }

    public function testGetMaxDate(): void
    {
        $this->assertEquals('now', $this->testFilter->getMaxDate());
    }

    public function testRender(): void
    {
        $renderResult = json_decode($this->testFilter->render('testAttribute', '1'), true);
        $this->assertEquals('grid_filter', $renderResult[0]);
    }
}