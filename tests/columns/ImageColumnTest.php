<?php


namespace Pfilsx\DataGrid\tests\columns;


use Pfilsx\DataGrid\Grid\Columns\ImageColumn;

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
        $this->testColumn = new ImageColumn($this->containerArray, [
            'attribute' => 'testAttribute',
            'width' => 20,
            'height' => 20,
            'alt' => function () {
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
        $this->assertEquals('Empty', $this->testColumn->getCellContent($entity));
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
        $content = json_decode($this->testColumn->getCellContent($entity), true);
        $this->assertEquals('grid_img', $content[0]);

        $column = new ImageColumn($this->containerArray, [
            'format' => 'raw',
            'attribute' => 'testAttribute'
        ]);
        $this->assertEquals('/path/to/image.jpg', $column->getCellContent($entity));
    }
}
