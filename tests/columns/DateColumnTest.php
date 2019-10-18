<?php


namespace Pfilsx\DataGrid\tests\columns;


use DateTime;
use Pfilsx\DataGrid\Grid\Columns\DateColumn;
use Pfilsx\DataGrid\Grid\Items\EntityGridItem;
use Pfilsx\tests\OrmTestCase;

/**
 * Class DateColumnTest
 * @package Pfilsx\DataGrid\tests
 *
 * @property DateColumn $testColumn
 */
class DateColumnTest extends OrmTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->testColumn = new DateColumn($this->containerArray, [
            'attribute' => 'testAttribute',
            'dateFormat' => 'm-d-Y',
            'template' => 'test_template.html.twig'
        ]);
    }

    public function testGetFormat(): void
    {
        $this->assertEquals('m-d-Y', $this->testColumn->getDateFormat());
    }

    public function testGetCellContent(): void
    {
        $entity = new class
        {
            public $date;

            public function getTestAttribute()
            {
                return $this->date;
            }
        };
        $item = new EntityGridItem($entity);
        $entity->date = new DateTime('01-01-1970');
        $this->assertEquals('01-01-1970', $this->testColumn->getCellContent($item));
        $entity->date = '01-01-1970';
        $this->assertEquals('01-01-1970', $this->testColumn->getCellContent($item));
    }

    public function testGetCellContentNoFormat(): void
    {
        $column = new DateColumn($this->containerArray, [
            'value' => function () {
                return '01-01-1970';
            },
            'dateFormat' => null,
            'template' => 'test_template.html.twig'
        ]);
        $this->assertEquals('01-01-1970', $column->getCellContent(null));
    }
}
