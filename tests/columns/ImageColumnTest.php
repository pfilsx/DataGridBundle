<?php


namespace Pfilsx\DataGrid\tests\columns;


use Pfilsx\DataGrid\Grid\Columns\ImageColumn;
use Pfilsx\DataGrid\Grid\DataGrid;
use Pfilsx\DataGrid\tests\BaseCase;
use Twig\Template;

/**
 * Class ImageColumnTest
 * @package Pfilsx\DataGrid\tests\columns
 *
 * @property ImageColumn $testColumn
 */
class ImageColumnTest extends ColumnCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->testColumn = new ImageColumn($this->container, [
            'attribute' => 'testAttribute',
            'width' => 20,
            'height' => 20,
            'alt' => function($entity){
                return 'Test alt';
            },
            'noImageMessage' => 'Empty'
        ]);
    }

    public function testGetWidth(): void
    {
        $this->assertEquals(20, $this->testColumn->getWidth());
    }

    public function testGetHeight(): void
    {
        $this->assertEquals(20, $this->testColumn->getHeight());
    }

    public function testGetAlt(): void
    {
        $this->assertEquals('Test alt', $this->testColumn->getAlt(null));
    }

    public function testGetNoImageMessage(): void
    {
        $this->assertEquals('Empty', $this->testColumn->getNoImageMessage());
        $entity = new class
        {
            public function getTestAttribute()
            {
                return null;
            }
        };
        $this->assertEquals('Empty', $this->testColumn->getCellContent($entity, $this->grid));
    }

    public function testGetCellContent(): void
    {
        $entity = new class
        {
            public function getTestAttribute()
            {
                return '/path/to/image.jpg';
            }
        };
        $this->assertEquals('', $this->testColumn->getCellContent($entity, $this->grid));

        $column = new ImageColumn($this->container, [
            'format' => 'raw',
            'attribute' => 'testAttribute'
        ]);
        $this->assertEquals('/path/to/image.jpg', $column->getCellContent($entity, $this->grid));
    }
}