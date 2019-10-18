<?php


namespace Pfilsx\tests\providers;


use Pfilsx\DataGrid\DataGridException;
use Pfilsx\DataGrid\Grid\Providers\ArrayDataProvider;
use Pfilsx\DataGrid\Grid\Providers\DataProvider;
use Pfilsx\DataGrid\Grid\Providers\QueryBuilderDataProvider;
use Pfilsx\DataGrid\Grid\Providers\RepositoryDataProvider;
use Pfilsx\tests\OrmTestCase;
use Pfilsx\tests\TestEntities\Node;

class DataProviderTest extends OrmTestCase
{
    public function testCreate(): void
    {
        $repository = $this->getEntityManager()->getRepository(Node::class);
        $provider1 = DataProvider::create($repository, $this->containerArray['doctrine']);
        $this->assertInstanceOf(RepositoryDataProvider::class, $provider1);

        $qb = $repository->createQueryBuilder('qb1');
        $provider2 = DataProvider::create($qb, $this->containerArray['doctrine']);
        $this->assertInstanceOf(QueryBuilderDataProvider::class, $provider2);

        $provider3 = DataProvider::create([], $this->containerArray['doctrine']);
        $this->assertInstanceOf(ArrayDataProvider::class, $provider3);

        $this->expectException(DataGridException::class);
        DataProvider::create('', $this->containerArray['doctrine']);
    }
}
