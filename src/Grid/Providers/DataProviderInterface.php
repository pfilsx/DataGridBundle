<?php


namespace Pfilsx\DataGrid\Grid\Providers;


use Pfilsx\DataGrid\Grid\Pager;

/**
 * Interface DataProviderInterface
 * @package Pfilsx\DataGrid\Grid\Providers
 * @internal
 */
interface DataProviderInterface
{
    public function getItems(): array;

    /**
     * @internal
     * @param Pager $pager
     */
    public function setPager(Pager $pager): void;

    /**
     * @internal
     * @return Pager
     */
    public function getPager(): Pager;

    public function getTotalCount(): int;

    public function setSort(array $sort): self;

    public function addEqualFilter(string $attribute, $value): self;

    public function addLikeFilter(string $attribute, $value): self;

    public function addRelationFilter(string $attribute, $value, string $relationClass): self;

    public function addCustomFilter(string $attribute, $value, callable $callback): self;

    public function addDateFilter(string $attribute, $value, string $comparison = 'equal'): self;

    public function setCountFieldName(string $name): self;
    
    public function getCountFieldName(): string;
}
