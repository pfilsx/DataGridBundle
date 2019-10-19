<?php


namespace Pfilsx\DataGrid\tests\filters;


use Pfilsx\DataGrid\Grid\Filters\DateFilter;
use Pfilsx\tests\OrmTestCase;

/**
 * Class DateFilterTest
 * @package Pfilsx\DataGrid\tests\filters
 *
 * @property DateFilter $testFilter
 */
class DateFilterTest extends OrmTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->testFilter = new DateFilter($this->serviceContainer, [
            'minDate' => '01-01-1990',
            'maxDate' => 'now',
            'template' => 'test_template.html.twig'
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
        $this->assertEquals('<input type="date"  class="data_grid_filter" name="testAttribute" value="01-01-1991"/>', trim($this->testFilter->render('testAttribute', '01-01-1991')));
    }
}
