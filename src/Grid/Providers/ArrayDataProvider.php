<?php


namespace Pfilsx\DataGrid\Grid\Providers;


use Doctrine\Common\Collections\Criteria;
use Pfilsx\DataGrid\Grid\Pager;

class ArrayDataProvider implements DataProviderInterface
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }


    public function getItems(): array
    {
        // TODO: Implement getItems() method.
    }


    public function getTotalCount(): int
    {
        // TODO: Implement getTotalCount() method.
    }

    public function getPager(): Pager
    {
        // TODO: Implement getPager() method.
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