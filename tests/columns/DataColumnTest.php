<?php


namespace Pfilsx\DataGrid\tests\columns;


use Pfilsx\DataGrid\DataGridException;
use Pfilsx\DataGrid\Grid\AbstractGridType;
use Pfilsx\DataGrid\Grid\Columns\AbstractColumn;
use Pfilsx\DataGrid\Grid\Columns\DataColumn;
use Pfilsx\DataGrid\Grid\Filters\AbstractFilter;

/**
 * Class DataColumnTest
 * @package Pfilsx\DataGrid\tests
 *
 * @property DataColumn $testColumn
 */
class DataColumnTest extends ColumnCase
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
            ]
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
        $filterContent = json_decode($this->testColumn->getFilterContent(), true);
        $this->assertEquals('grid_filter', $filterContent[0]);
    }

    public function testGetHeadContent(): void
    {
        $this->assertEquals('Test', $this->testColumn->getHeadContent());
        $column = new DataColumn($this->containerArray, ['attribute' => 'testAttribute']);
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
        $this->assertEquals('test_data', $this->testColumn->getCellContent($entity));

        $column = new DataColumn($this->containerArray, ['value' => function () {
            return 'test_data';
        }]);

        $this->assertIsCallable($column->getValue());
        $this->assertEquals('test_data', $column->getCellContent($entity));

        $column = new DataColumn($this->containerArray, ['value' => 'test_data']);
        $this->assertEquals('test_data', $column->getCellContent($entity));
    }

    public function testWrongAttribute(): void
    {
        $this->expectException(DataGridException::class);
        $entity = new class
        {
        };
        $this->testColumn->getCellContent($entity);
    }
}
