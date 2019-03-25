<?php


namespace Pfilsx\DataGrid\Grid;


use Doctrine\Common\Collections\Criteria;

interface DataGridFiltersBuilderInterface
{
    public function addEqualFilter(string $attribute) : self;

    public function addLikeFilter(string $attribute): self;

    public function addRelationFilter(string $attribute, string $relationClass): self;

    public function addCustomFilter(string $attribute, callable $callback): self;

    public function addDateFilter(string $attribute, string $comparison = 'equal'): self;

    /**
     * @internal
     * @return Criteria
     */
    public function getCriteria(): Criteria;

    /**
     * @param array $params
     */
    public function setParams(array $params):void;
}
