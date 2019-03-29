<?php


namespace Pfilsx\DataGrid\Grid\Providers;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Pfilsx\DataGrid\DataGridException;

abstract class DataProvider
{
    public static function create($data): DataProviderInterface
    {
        if ($data instanceof ServiceEntityRepository) {
            return new RepositoryDataProvider($data);
        }
        if ($data instanceof QueryBuilder) {
            return new QueryBuilderDataProvider($data);
        }
        if (is_array($data)) {
            return new ArrayDataProvider($data);
        }
        throw new DataGridException('Provided data must be one of: ' . implode(',', [
                ServiceEntityRepository::class,
                QueryBuilder::class,
                'Array'
            ]));
    }
}