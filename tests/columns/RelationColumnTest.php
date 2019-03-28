<?php


namespace Pfilsx\DataGrid\tests\columns;


use Pfilsx\DataGrid\DataGridException;
use Pfilsx\DataGrid\Grid\Columns\RelationColumn;

class RelationColumnTest extends ColumnCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->testColumn = new RelationColumn($this->containerArray, [
            'attribute' => 'test_attribute',
            'labelAttribute' => 'title',
            'format' => 'html',
            'label' => 'test'
        ]);
    }

    public function testCheckConfiguration(): void
    {
        $this->expectException(DataGridException::class);
        new RelationColumn($this->containerArray, [
            'attribute' => 'test_attribute'
        ]);
    }

    public function testGetHeadContent(): void
    {
        $this->assertEquals('Test', $this->testColumn->getHeadContent());
        $column = new RelationColumn($this->containerArray, ['attribute' => 'testAttribute', 'labelAttribute' => 'title']);
        $this->assertEquals('TestAttribute.Title', $column->getHeadContent());
    }

    public function testGetCellContent(): void
    {
        $entity = new class
        {
            public function getTestAttribute()
            {
                return new class
                {
                    public function getTitle()
                    {
                        return 'test_data';
                    }
                };
            }
        };
        $this->assertEquals('test_data', $this->testColumn->getCellContent($entity));

        $this->assertEquals('', $this->testColumn->getCellContent(new class
        {
            public function getTestAttribute()
            {
                return null;
            }
        }));
    }
}
