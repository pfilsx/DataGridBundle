<?php


namespace Pfilsx\tests\providers;


use DateTime;
use Pfilsx\DataGrid\Grid\Items\ArrayGridItem;
use Pfilsx\DataGrid\Grid\Pager;
use Pfilsx\DataGrid\Grid\Providers\ArrayDataProvider;
use Pfilsx\DataGrid\Grid\Providers\DataProvider;
use PHPUnit\Framework\TestCase;

class ArrayDataProviderTest extends TestCase
{
    /**
     * @var DataProvider
     */
    protected $provider;


    protected function setUp(): void
    {
        $itemsSets = $this->getItemSets();

        $this->provider = DataProvider::create([
            $itemsSets[1],
            $itemsSets[3],
            $itemsSets[2],
            $itemsSets[4]
        ]);
        $this->provider->setPager(new Pager());
    }

    public function testType(): void
    {
        $this->assertInstanceOf(ArrayDataProvider::class, $this->provider);
    }

    public function testGetItems(): void
    {
        $items = $this->provider->getItems();
        $this->assertIsArray($items);
        $this->assertCount(4, $items);
        $this->assertEquals(4, $this->provider->getTotalCount());
    }

    /**
     * @dataProvider sortDataProvider
     * @param $attr
     * @param $result
     */
    public function testSetSort($attr, $result): void
    {
        $this->assertEquals($result, $this->provider->setSort([$attr => 'ASC'])->getItems());
        if (in_array($attr, ['id', 'title'])) {
            $this->assertEquals(array_reverse($result), $this->provider->setSort([$attr => 'DESC'])->getItems());
        }
    }

    /**
     * @dataProvider filterDataProvider
     * @param $attr
     * @param $value
     * @param $resultCount
     */
    public function testEqualFilter($attr, $value, $resultCount): void
    {
        $this->assertCount($resultCount, $this->provider->addEqualFilter($attr, $value)->getItems());
    }

    public function testLikeFilter(): void
    {
        $provider1 = clone($this->provider);
        $this->assertCount(4, $provider1->addLikeFilter('title', 'est')->getItems());
        $this->assertCount(0, $provider1->addLikeFilter('title', 't45')->getItems());
        $this->assertCount(0, $this->provider->addLikeFilter('customAttr', 'est')->getItems());
    }

    public function testCustomFilter(): void
    {
        $this->assertCount(1, $this->provider->addCustomFilter('id', 3, function ($row, $attr, $val) {
            return $attr == 'id' && $row[$attr] == $val;
        })->getItems());
    }

    /**
     * @dataProvider dateFilterDataProvider
     * @param $comparer
     * @param $value
     * @param $resultCount
     */
    public function testDateFilter($comparer, $value, $resultCount): void
    {
        $this->assertCount($resultCount, $this->provider->addDateFilter('created', $value, $comparer)->getItems());
    }

    /**
     * @dataProvider missingDateFilterDataProvider
     * @param $comparer
     */
    public function testMissingAttributeDateFilter($comparer): void
    {
        $this->assertCount(0, $this->provider->addDateFilter('customAttr', '01.01.1990', $comparer)->getItems());
    }

    /**
     * @dataProvider missingDateFilterDataProvider
     * @param $comparer
     */
    public function testWrongTypeDateFilter($comparer): void
    {
        $provider = DataProvider::create([
            [
                'created' => 1
            ]
        ]);
        $provider->setPager(new Pager());
        $this->assertCount(0, $provider->addDateFilter('created', '01.01.1990', $comparer)->getItems());
    }

    public function sortDataProvider(): array
    {
        $itemSets = $this->getItemSets();
        $item1 = new ArrayGridItem($itemSets[1], 'id');
        $item2 = new ArrayGridItem($itemSets[2], 'id');
        $item3 = new ArrayGridItem($itemSets[3], 'id');
        $item4 = new ArrayGridItem($itemSets[4], 'id');
        return [
            [
                'id',
                [
                    $item1,
                    $item2,
                    $item3,
                    $item4
                ]
            ],
            [
                'title',
                [
                    $item1,
                    $item3,
                    $item2,
                    $item4
                ]
            ],
            [
                'created',
                [
                    $item1,
                    $item3,
                    $item2,
                    $item4,
                ]
            ],
            [
                'is_active',
                [
                    $item3,
                    $item4,
                    $item1,
                    $item2,
                ]
            ],
            [
                'customAttr',
                [
                    $item1,
                    $item3,
                    $item2,
                    $item4
                ]
            ]
        ];
    }

    public function filterDataProvider(): array
    {
        return [
            ['id', 3, 1],
            ['title', 'test3', 1],
            ['created', new DateTime('01.01.1990'), 1],
            ['customAttr', 0, 0]
        ];
    }

    public function dateFilterDataProvider(): array
    {
        return [
            ['equal', '01.01.1990', 1],
            ['gt', '02.01.1990', 2],
            ['gte', '02.01.1990', 3],
            ['lt', '05.01.1990', 3],
            ['lte', '05.01.1990', 4],
        ];
    }

    public function missingDateFilterDataProvider(): array
    {
        return [
            ['equal'],
            ['lt'],
            ['lte'],
            ['gt'],
            ['gte'],
        ];
    }

    protected function getItemSets()
    {
        return [
            1 => [
                'id' => 1,
                'title' => 'test1',
                'created' => new DateTime('01.01.1990'),
                'is_active' => true
            ],
            [
                'id' => 2,
                'title' => 'test3',
                'created' => new DateTime('05.01.1990'),
                'is_active' => true
            ],
            [
                'id' => 3,
                'title' => 'test2',
                'created' => new DateTime('02.01.1990'),
                'is_active' => false
            ],
            [
                'id' => 4,
                'title' => 'test4',
                'created' => '03.01.1990',
                'is_active' => false
            ]
        ];
    }
}
