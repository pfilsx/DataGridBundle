<?php


namespace Pfilsx\DataGrid\tests;


use Pfilsx\DataGrid\Grid\Pager;
use PHPUnit\Framework\TestCase;

class PagerTest extends TestCase
{
    public function testSetOptions(): void
    {
        $pager = new Pager();
        $pager->setOptions([
            'limit' => 10,
            'page' => 1
        ]);

        $this->assertEquals(10, $pager->getLimit());
        $this->assertEquals(1, $pager->getPage());
    }

    public function testEnableDisable(): void
    {
        $pager = new Pager();
        $pager->enable();
        $this->assertTrue($pager->isEnabled());

        $pager->disable();
        $this->assertFalse($pager->isEnabled());
    }

    /**
     * @dataProvider paginationOptionsProvider
     * @param int $page
     * @param int $count
     * @param array $result
     */
    public function testGetPaginationOptions(int $page, int $count, array $result): void
    {
        $pager = new Pager();
        $pager->setLimit(1);

        $pager->setPage($page);
        $pager->setTotalCount($count);
        $this->assertEquals($result, $pager->getPaginationOptions());
    }

    public function testGetFirst(): void
    {
        $pager = new Pager();
        $pager->setLimit(10);
        $pager->setPage(1);

        $this->assertEquals(0, $pager->getFirst());

        $pager->setPage(2);
        $this->assertEquals(10, $pager->getFirst());
    }

    public function paginationOptionsProvider()
    {
        return [
            [15, 0, ['currentPage' => 1, 'pages' => [1]]],
            [1, 0, ['currentPage' => 1, 'pages' => [1]]],
            [1, 5, ['currentPage' => 1, 'pages' => [1, 2, 3, 4, 5]]],
            [1, 15, ['currentPage' => 1, 'pages' => [1, 2, 3, 4, 5, 6, null, 15]]],
            [15, 15, ['currentPage' => 15, 'pages' => [1, null, 10, 11, 12, 13, 14, 15]]],
            [7, 15, ['currentPage' => 7, 'pages' => [1, null, 5, 6, 7, 8, 9, null, 15]]]
        ];
    }
}
