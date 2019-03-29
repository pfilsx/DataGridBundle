<?php


namespace Pfilsx\DataGrid\Grid\Providers;


use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Pfilsx\DataGrid\Grid\Pager;

class QueryBuilderDataProvider implements DataProviderInterface
{

    protected $builder;

    public function __construct(QueryBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function getItems(): array
    {
        // TODO: Implement getItems() method.
    }

    public function getPager(): Pager
    {
        return new Pager([]);
    }

    public function getTotalCount(): int
    {
        // TODO: Implement getTotalCount() method.
    }

    public function setSort(array $sort): DataProviderInterface
    {
        // TODO: Implement setSort() method.
    }

    public function setPagerConfiguration(array $pagerConfiguration): DataProviderInterface
    {
        // TODO: Implement setPagerConfiguration() method.
    }

    public function setCriteria(Criteria $criteria): DataProviderInterface
    {
        // TODO: Implement setCriteria() method.
    }
}