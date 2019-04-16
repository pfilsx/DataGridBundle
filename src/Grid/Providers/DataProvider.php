<?php


namespace Pfilsx\DataGrid\Grid\Providers;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Pfilsx\DataGrid\DataGridException;
use Pfilsx\DataGrid\Grid\Pager;

abstract class DataProvider implements DataProviderInterface
{
    /**
     * @var Pager
     */
    protected $pager;

    protected $pagerConfiguration = [];

    /**
     * @internal
     * @return Pager
     */
    public function getPager(): Pager
    {
        return $this->pager ?? ($this->pager = new Pager(array_merge($this->pagerConfiguration, [
                'totalCount' => $this->getTotalCount()
            ])));
    }

    /**
     * @internal
     * @param array $pagerConfiguration
     * @return DataProviderInterface
     */
    public function setPagerConfiguration(array $pagerConfiguration): DataProviderInterface
    {
        $this->pagerConfiguration = $pagerConfiguration;
        return $this;
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