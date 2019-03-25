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
        $this->testFilter = new ChoiceFilter($this->containerArray, [
            'choices' => [0, 1, 2, 3]
        ]);
    }

    public function testGetChoices(): void
    {
        $this->assertEquals([0, 1, 2, 3], $this->testFilter->getChoices());
    }

    public function testRender(): void
    {
        $renderResult = json_decode($this->testFilter->render('testAttribute', '1'), true);
        $this->assertEquals('grid_filter', $renderResult[0]);
    }
}
