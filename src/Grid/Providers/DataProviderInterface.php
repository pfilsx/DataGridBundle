<?php


namespace Pfilsx\DataGrid\Grid\Providers;


use Doctrine\Common\Collections\Criteria;
use Pfilsx\DataGrid\Grid\Pager;

interface DataProviderInterface
{
    public function getItems(): array;

    public function getPager(): Pager;

    public function getTotalCount(): int;

    public function setSort(array $sort): self;

    public function setPagerConfiguration(array $pagerConfiguration): self;

    public function setCriteria(Criteria $criteria): self;

}
