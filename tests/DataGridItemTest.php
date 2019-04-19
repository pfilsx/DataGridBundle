<?php


namespace Pfilsx\DataGrid\tests;


use Pfilsx\DataGrid\DataGridException;
use Pfilsx\DataGrid\Grid\DataGridItem;
use PHPUnit\Framework\TestCase;

class DataGridItemTest extends TestCase
{


    public function testRow(): void
    {
        $item = new DataGridItem();
        $row = [
            'id' => 1
        ];
        $item->setRow($row);
        $this->assertIsArray($item->getRow());
        $this->assertEquals($row, $item->getRow());
    }

    public function testHasOnEmpty(): void
    {
        $item = new DataGridItem();
        $this->assertFalse($item->has('id'));
    }

    /**
     * @dataProvider itemsProvider
     * @param DataGridItem $item
     */
    public function testHas(DataGridItem $item): void
    {
        $this->assertTrue($item->has('id'));
        $this->assertFalse($item->has('test'));
    }

    public function testGetOnEmpty(): void
    {
        $item = new DataGridItem();
        $this->assertNull($item->get('id'));
    }

    /**
     * @dataProvider itemsProvider
     * @param DataGridItem $item
     */
    public function testGet(DataGridItem $item)
    {
        $this->assertEquals(1, $item->get('id'));

        $this->expectException(DataGridException::class);
        $item->get('test');
    }

    public function itemsProvider()
    {
        $item = new DataGridItem();
        $entity = new class
        {
            protected $id = 1;

            public function getId()
            {
                return $this->id;
            }
        };
        $item->setEntity($entity);

        $item2 = new DataGridItem();
        $item2->setRow([
            'id' => 1
        ]);


        return [
            [$item],
            [$item2]
        ];
    }
}
