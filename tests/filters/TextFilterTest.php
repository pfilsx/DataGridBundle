<?php


namespace Pfilsx\DataGrid\tests\filters;


use Pfilsx\DataGrid\Grid\Filters\TextFilter;
use Pfilsx\tests\OrmTestCase;

/**
 * @property TextFilter testFilter
 */
class TextFilterTest extends OrmTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->testFilter = new TextFilter($this->serviceContainer, [
            'template' => 'test_template.html.twig'
        ]);
    }

    public function testGetBlockName(): void
    {
        $this->assertEquals('text_filter', $this->testFilter->getBlockName());
    }
}
