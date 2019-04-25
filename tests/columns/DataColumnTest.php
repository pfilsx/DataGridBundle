<?php


namespace Pfilsx\DataGrid\tests\columns;


use Pfilsx\DataGrid\DataGridException;
use Pfilsx\DataGrid\Grid\AbstractGridType;
use Pfilsx\DataGrid\Grid\Columns\AbstractColumn;
use Pfilsx\DataGrid\Grid\Columns\DataColumn;
use Pfilsx\DataGrid\Grid\DataGridItem;
use Pfilsx\DataGrid\Grid\Filters\AbstractFilter;
use Pfilsx\tests\OrmTestCase;

/**
 * Class DataColumnTest
 * @package Pfilsx\DataGrid\tests
 *
 * @property DataColumn $testColumn
 */
class DataColumnTest extends OrmTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->testColumn = new DataColumn($this->containerArray, [
            'attribute' => 'test_attribute',
            'format' => 'html',
            'label' => 'test',
            'visible' => false,
            'attributes' => [
                'class' => 'test_class',
                'data_row' => false
            ],
            'template' => 'test_template.html.twig'
        ]);
    }

    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(AbstractColumn::class, $this->testColumn);
        $this->assertInstanceOf(DataColumn::class, $this->testColumn);

    }

    public function testAttributeOrValueException(): void
    {
        $this->expectException(DataGridException::class);
        new DataColumn($this->containerArray);
    }

    public function testGetAttribute(): void
    {
        $this->assertEquals('test_attribute', $this->testColumn->getAttribute());
    }

    public function testGetFormat(): void
    {
        $this->assertEquals('html', $this->testColumn->getFormat());
    }

    public function testGetAttributes(): void
    {
        $this->assertEquals([
            'class' => 'test_class',
            'data_row' => false
        ], $this->testColumn->getAttributes());
    }

    public function testGetIsVisible(): void
    {
        $this->assertFalse($this->testColumn->isVisible());
    }

    public function testGetSort(): void
    {
        $this->assertTrue($this->testColumn->hasSort());
        $this->testColumn->setSort('UP');
        $this->assertEquals('UP', $this->testColumn->getSort());
    }

    public function testFilter(): void
    {
        $this->assertFalse($this->testColumn->hasFilter());
        $this->assertEquals('', $this->testColumn->getFilterContent());
        $this->testColumn->setFilter([
            'class' => AbstractGridType::FILTER_TEXT
        ]);
        $this->assertTrue($this->testColumn->hasFilter());
        $this->assertInstanceOf(AbstractFilter::class, $this->testColumn->getFilter());
        $this->assertEquals('<input type="text"  class="data_grid_filter" name="test_attribute" value="">', trim($this->testColumn->getFilterContent()));
    }

    public function testGetHeadContent(): void
    {
        $this->assertEquals('Test', $this->testColumn->getHeadContent());
        $column = new DataColumn($this->containerArray, [
            'attribute' => 'testAttribute',
            'template' => 'test_template.html.twig'
        ]);
        $this->assertEquals('TestAttribute', $column->getHeadContent());
    }

    public function testCellContent(): void
    {
        $entity = new class
        {
            public $data = 'test_data';

            public function getTestAttribute()
            {
                return $this->data;
            }
        };
        $item = new DataGridItem();
        $item->setEntity($entity);
        $this->assertEquals('test_data', $this->testColumn->getCellContent($item));

        $column = new DataColumn($this->containerArray, [
            'value' => function () {
                return 'test_data';
            },
            'template' => 'test_template.html.twig'
        ]);

        $this->assertIsCallable($column->getValue());
        $this->assertEquals('test_data', $column->getCellContent($item));

        $column = new DataColumn($this->containerArray, [
            'value' => 'test_data',
            'template' => 'test_template.html.twig'
        ]);
        $this->assertEquals('test_data', $column->getCellContent($item));
    }

    public function testWrongAttribute(): void
    {
        $this->expectException(DataGridException::class);
        $entity = new class
        {
        };
        $item = new DataGridItem();
        $item->setEntity($entity);
        $this->testColumn->getCellContent($item);
    }
}
