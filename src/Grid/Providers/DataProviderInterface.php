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


    public function addEqualFilter(string $attribute, $value): self;

    public function addLikeFilter(string $attribute, $value): self;

    public function addRelationFilter(string $attribute, $value, string $relationClass): self;

    public function addCustomFilter(string $attribute, $value, callable $callback): self;

    public function addDateFilter(string $attribute, $value, string $comparison = 'equal'): self;
}
