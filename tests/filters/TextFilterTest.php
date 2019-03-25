<?php


namespace Pfilsx\DataGrid\tests\filters;


use Pfilsx\DataGrid\Grid\Filters\TextFilter;
use Pfilsx\DataGrid\tests\BaseCase;

class TextFilterTest extends BaseCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->testFilter = new TextFilter($this->containerArray);
    }

    public function testGetBlockName(): void
    {
        $this->assertEquals('text_filter', $this->testFilter->getBlockName());
    }
}
