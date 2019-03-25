<?php

namespace Pfilsx\DataGrid\tests\filters;

use Pfilsx\DataGrid\Grid\Filters\BooleanFilter;
use Pfilsx\DataGrid\tests\BaseCase;

/**
 * Class BooleanFilterTest
 * @package Pfilsx\DataGrid\tests\filters
 *
 * @property BooleanFilter $testFilter
 */
class BooleanFilterTest extends BaseCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->testFilter = new BooleanFilter($this->containerArray, [
            'trueChoice' => 'yes',
            'falseChoice' => 'no'
        ]);
    }

    public function testGetTrueChoice(): void
    {
        $this->assertEquals('yes', $this->testFilter->getTrueChoice());
    }

    public function testGetFalseChoice(): void
    {
        $this->assertEquals('no', $this->testFilter->getFalseChoice());
    }

    public function testRender(): void
    {
        $renderResult = json_decode($this->testFilter->render('testAttribute', '1'), true);
        $this->assertEquals('grid_filter', $renderResult[0]);
    }
}
