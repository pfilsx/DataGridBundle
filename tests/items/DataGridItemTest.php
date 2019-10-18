<?php


namespace Pfilsx\tests\items;


use Pfilsx\DataGrid\DataGridException;
use Pfilsx\DataGrid\Grid\Items\ArrayGridItem;
use Pfilsx\DataGrid\Grid\Items\EntityGridItem;
use PHPUnit\Framework\TestCase;

class DataGridItemTest extends TestCase
{

    /**
     * @param EntityGridItem|ArrayGridItem $item
     * @dataProvider getGridItems
     */
    public function testHas($item)
    {
        $this->assertTrue($item->has('id'));
        $this->assertTrue(!empty($item['id']));
        $this->assertTrue(isset($item->id));

        $this->assertTrue($item->has('name'));
        $this->assertTrue(!empty($item['name']));
        $this->assertTrue(isset($item->name));

        $this->assertTrue($item->has('created_by'));
        $this->assertTrue(!empty($item['created_by']));
        $this->assertTrue(isset($item->created_by));

        $this->assertFalse($item->has('test'));
        $this->assertFalse(!empty($item['test']));
        $this->assertFalse(isset($item->test));
    }

    /**
     * @param EntityGridItem|ArrayGridItem $item
     * @dataProvider getGridItems
     */
    public function testGet($item)
    {
        $this->assertEquals(1, $item->get('id'));
        $this->assertEquals(1, $item->id);
        $this->assertEquals(1, $item['id']);

        $this->assertEquals('FooBar', $item->get('name'));
        $this->assertEquals('FooBar', $item->name);
        $this->assertEquals('FooBar', $item['name']);

        $this->assertEquals('author', $item->get('created_by'));
        $this->assertEquals('author', $item->created_by);
        $this->assertEquals('author', $item['created_by']);

        $this->expectException(DataGridException::class);
        $item->get('test');
    }

    /**
     * @param EntityGridItem|ArrayGridItem $item
     * @param array|object $result
     * @dataProvider getGridItems
     */
    public function testGetData($item, $result)
    {
        $this->assertEquals($result, $item->getData());
    }
    /**
     * @param EntityGridItem|ArrayGridItem $item
     * @dataProvider getGridItems
     */
    public function testSet($item)
    {
        $this->expectException(DataGridException::class);
        $item->test = 'FooBar';
    }
    /**
     * @param EntityGridItem|ArrayGridItem $item
     * @dataProvider getGridItems
     */
    public function testAccessSet($item)
    {
        $this->expectException(DataGridException::class);
        $item['test'] = 'FooBar';
    }
    /**
     * @param EntityGridItem|ArrayGridItem $item
     * @dataProvider getGridItems
     */
    public function testUnset($item)
    {
        $this->expectException(DataGridException::class);
        unset($item->id);
    }
    /**
     * @param EntityGridItem|ArrayGridItem $item
     * @dataProvider getGridItems
     */
    public function testAccessUnset($item)
    {
        $this->expectException(DataGridException::class);
        unset($item['id']);
    }


    public function getGridItems()
    {
        $entity = new class
        {
            private $id = 1;

            public $name = 'FooBar';

            public $createdBy = 'author';

            public function getId()
            {
                return $this->id;
            }
        };
        $row = [
            'id' => 1,
            'name' => 'FooBar',
            'created_by' => 'author'
        ];

        return [
            [new ArrayGridItem($row, 'id'), $row],
            [new EntityGridItem($entity, 'id'), $entity]
        ];
    }
}