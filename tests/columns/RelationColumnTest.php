<?php


namespace Pfilsx\DataGrid\tests\columns;


use Pfilsx\DataGrid\DataGridException;
use Pfilsx\DataGrid\Grid\Columns\RelationColumn;
use Pfilsx\DataGrid\Grid\DataGridItem;
use Pfilsx\tests\OrmTestCase;

/**
 * @property RelationColumn testColumn
 */
class RelationColumnTest extends OrmTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->testColumn = new RelationColumn($this->containerArray, [
            'attribute' => 'test_attribute',
            'labelAttribute' => 'title',
            'format' => 'html',
            'label' => 'test',
            'template' => 'test_template.html.twig'
        ]);
    }

    public function testCheckConfiguration(): void
    {
        $this->expectException(DataGridException::class);
        new RelationColumn($this->containerArray, [
            'attribute' => 'test_attribute',
            'template' => 'test_template.html.twig'
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
        $item = new DataGridItem();
        $item->setEntity($entity);
        $this->assertEquals('test_data', $this->testColumn->getCellContent($item));

        $item->setEntity(new class
        {
            public function getTestAttribute()
            {
                return null;
            }
        });
        $this->assertEquals('', $this->testColumn->getCellContent($item));
    }
}
