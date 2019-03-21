<?php


namespace Pfilsx\DataGrid\tests\columns;


use DateTime;
use Pfilsx\DataGrid\Grid\Columns\DateColumn;
use Pfilsx\DataGrid\tests\BaseCase;

/**
 * Class DateColumnTest
 * @package Pfilsx\DataGrid\tests
 *
 * @property DateColumn $testColumn
 */
class DateColumnTest extends BaseCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->testColumn = new DateColumn($this->container, [
            'attribute' => 'testAttribute',
            'dateFormat' => 'm-d-Y'
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
        $entity->date = new DateTime('01-01-1970');
        $this->assertEquals('01-01-1970', $this->testColumn->getCellContent($entity, null));
        $entity->date = '01-01-1970';
        $this->assertEquals('01-01-1970', $this->testColumn->getCellContent($entity, null));
    }

    public function testGetCellContentNoFormat(): void
    {
        $column = new DateColumn($this->container, [
            'value' => function(){return '01-01-1970';},
            'dateFormat' => null
        ]);
        $this->assertEquals('01-01-1970', $column->getCellContent(null, null));
    }
}