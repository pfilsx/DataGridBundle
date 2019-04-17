<?php


namespace Pfilsx\DataGrid\Grid\Providers;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Pfilsx\DataGrid\DataGridException;
use Pfilsx\DataGrid\Grid\Pager;

abstract class DataProvider implements DataProviderInterface
{
    protected $pager;

    protected $countFieldName;

    /**
     * @internal
     * @return Pager
     */
    public function getPager(): Pager
    {
        return $this->pager;
    }

    /**
     * @internal
     * @param Pager $pager
     */
    public function setPager(Pager $pager): void
    {
        $this->pager = $pager;
    }

    public function setCountFieldName(string $name): DataProviderInterface
    {
        $this->countFieldName = $name;
        return $this;
    }

    public function getCountFieldName(): string
    {
        return $this->countFieldName;
    }

    public static function create($data, ManagerRegistry $doctrine): DataProviderInterface
    {
        if ($data instanceof ServiceEntityRepository) {
            return new RepositoryDataProvider($data, $doctrine);
        }
        if ($data instanceof QueryBuilder) {
            return new QueryBuilderDataProvider($data, $doctrine);
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