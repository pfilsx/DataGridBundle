<?php


namespace Pfilsx\DataGrid\tests\filters;


use Pfilsx\DataGrid\Grid\Filters\ChoiceFilter;
use Pfilsx\DataGrid\tests\BaseCase;

/**
 * Class ChoiceFilterTest
 * @package Pfilsx\DataGrid\tests\filters
 *
 * @property ChoiceFilter $testFilter
 */
class ChoiceFilterTest extends BaseCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->testFilter = new ChoiceFilter($this->container, [
            'choices' => [0, 1, 2, 3]
        ]);
    }

    public function testGetChoices(): void
    {
        $this->assertEquals([0,1,2,3], $this->testFilter->getChoices());
    }

    public function testRender(): void
    {
        $this->assertEquals('grid_filter', $this->testFilter->render('testAttribute', '1'));
    }
}