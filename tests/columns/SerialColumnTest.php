<?php


namespace Pfilsx\DataGrid\tests\columns;


use Pfilsx\DataGrid\Grid\Columns\SerialColumn;

/**
 * Class SerialColumnTest
 * @package Pfilsx\DataGrid\tests\columns
 *
 * @property SerialColumn $testColumn
 */
class SerialColumnTest extends ColumnCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->testColumn = new SerialColumn($this->containerArray);
    }

    public function testGetHeadContent(): void
    {
        $this->assertEquals('#', $this->testColumn->getHeadContent());
    }

    public function testFilter(): void
    {
        $this->assertFalse($this->testColumn->hasFilter());
        $this->assertEmpty($this->testColumn->getFilterContent());
    }

    public function testGetCellContent(): void
    {
        $this->assertEquals(1, $this->testColumn->getCellContent(null));
        $this->assertEquals(2, $this->testColumn->getCellContent(null));
    }
}
