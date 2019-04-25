<?php


namespace Pfilsx\DataGrid\Grid;


use Pfilsx\DataGrid\Grid\Providers\DataProviderInterface;

interface DataGridFiltersBuilderInterface
{
    public function addEqualFilter(string $attribute): self;

    public function addLikeFilter(string $attribute): self;

    public function addRelationFilter(string $attribute, string $relationClass): self;

    public function addCustomFilter(string $attribute, callable $callback): self;

    public function addDateFilter(string $attribute, string $comparison = 'equal'): self;

    /**
     * @param array $params
     * @return DataGridFiltersBuilderInterface
     */
    public function setParams(array $params): self;


    public function getProvider(): DataProviderInterface;

    /**
     * @internal
     * @param DataProviderInterface $provider
     */
    public function setProvider(DataProviderInterface $provider): void;
}
