<?php


namespace Pfilsx\tests\providers;


use Pfilsx\DataGrid\DataGridException;
use Pfilsx\DataGrid\Grid\Pager;
use Pfilsx\DataGrid\Grid\Providers\DataProvider;
use Pfilsx\DataGrid\Grid\Providers\QueryBuilderDataProvider;
use Pfilsx\tests\OrmTestCase;
use Pfilsx\tests\TestEntities\Node;

class QueryBuilderDataProviderTest extends OrmTestCase
{
    /**
     * @var DataProvider
     */
    protected $provider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->provider = DataProvider::create($this->getEntityManager()->getRepository(Node::class)->createQueryBuilder('qb1'), $this->serviceContainer->getDoctrine());
        $this->provider->setPager(new Pager());
    }

    public function testType(): void
    {
        $this->assertInstanceOf(QueryBuilderDataProvider::class, $this->provider);
    }

    public function testMissedCountField(): void
    {
        $this->expectException(DataGridException::class);
        $this->provider->getTotalCount();
    }

    public function testGetItems(): void
    {
        $this->provider->setCountFieldName('qb1.id');
        $this->assertCount($this->provider->getTotalCount(), $this->provider->setSort(['qb1.id' => 'DESC'])->getItems());
    }

    public function testAddEqualFilter(): void
    {
        $provider = clone($this->provider);
        $this->assertCount(1, $provider->addEqualFilter('qb1.id', 1)->getItems());

        $provider2 = clone($this->provider);
        $this->assertCount(0, $provider2->addEqualFilter('qb1.id', null)->getItems());
    }

    public function testAddLikeFilter(): void
    {
        $this->assertCount(5, $this->provider->addLikeFilter('qb1.user', 'to')->getItems());
    }

    public function testAddCustomFilter(): void
    {
        $this->assertCount(1, $this->provider->addCustomFilter('qb1.id', 1, function ($qb, $attribute, $value) {
            $qb->andWhere($qb->expr()->eq($attribute, $value));
        })->getItems());
    }

    /**
     * @dataProvider dateFilterDataProvider
     * @param $comparer
     * @param $value
     * @param $resultCount
     */
    public function testAddDateFilter($comparer, $value, $resultCount): void
    {
        $this->assertCount($resultCount, $this->provider->addDateFilter('qb1.createdAt', $value, $comparer)->getItems());
    }

    public function dateFilterDataProvider(): array
    {
        return [
            ['equal', '2010-04-24', 1],
            ['gt', '2010-04-24', 5],
            ['gte', '2010-04-24', 6],
            ['lt', '2010-04-20', 3],
            ['lte', '2010-04-20', 4],
        ];
    }
}