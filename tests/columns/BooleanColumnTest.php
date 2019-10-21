<?php


namespace Pfilsx\DataGrid\tests\columns;

use Pfilsx\DataGrid\Grid\Columns\AbstractColumn;
use Pfilsx\DataGrid\Grid\Columns\BooleanColumn;
use Pfilsx\DataGrid\Grid\Columns\DataColumn;
use Pfilsx\DataGrid\Grid\Items\EntityGridItem;
use Pfilsx\tests\OrmTestCase;

/**
 * Class BooleanColumnTest
 * @package Pfilsx\DataGrid\tests
 *
 * @property BooleanColumn $testColumn
 */
class BooleanColumnTest extends OrmTestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->testColumn = new BooleanColumn($this->serviceContainer, [
            'attribute' => 'testAttribute',
            'trueValue' => 'yes',
            'falseValue' => 'no',
            'template' => 'test_template.html.twig'
        ]);
    }

    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(AbstractColumn::class, $this->testColumn);
        $this->assertInstanceOf(DataColumn::class, $this->testColumn);

    }

    public function testGetTrueValue(): void
    {
        $this->assertEquals('yes', $this->testColumn->getTrueValue());
    }

    public function testGetFalseValue(): void
    {
        $this->assertEquals('no', $this->testColumn->getFalseValue());
    }

    public function testCellContent(): void
    {
        $entity = new class
        {
            public $enabled = true;

            public function getTestAttribute()
            {
                return $this->enabled;
            }
        };
        $item = new EntityGridItem($entity);
        $this->assertEquals('yes', $this->testColumn->getCellContent($item));
        $entity->enabled = false;
        $this->assertEquals('no', $this->testColumn->getCellContent($item));

        $column = new BooleanColumn($this->serviceContainer, [
            'value' => function () {
                return 'no';
            },
            'template' => 'test_template.html.twig'
        ]);

        $this->assertIsCallable($column->getValue());
        $this->assertEquals('no', $column->getCellContent($item));

        $column = new BooleanColumn($this->serviceContainer, ['value' => false]);
        $this->assertEquals('No', $column->getCellContent($item));

    }
}
