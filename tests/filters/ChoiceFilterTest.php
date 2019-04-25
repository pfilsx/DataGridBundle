<?php


namespace Pfilsx\DataGrid\tests\filters;


use Pfilsx\DataGrid\Grid\Filters\ChoiceFilter;
use Pfilsx\tests\OrmTestCase;

/**
 * Class ChoiceFilterTest
 * @package Pfilsx\DataGrid\tests\filters
 *
 * @property ChoiceFilter $testFilter
 */
class ChoiceFilterTest extends OrmTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->testFilter = new ChoiceFilter($this->containerArray, [
            'choices' => [0, 1, 2, 3],
            'template' => 'test_template.html.twig'
        ]);
    }

    public function testGetChoices(): void
    {
        $this->assertEquals([0, 1, 2, 3], $this->testFilter->getChoices());
    }

    public function testRender(): void
    {
        $this->assertEquals('select>option>0:0 option>1:1 option>2:2 option>3:3', trim($this->testFilter->render('testAttribute', '1')));
    }
}
