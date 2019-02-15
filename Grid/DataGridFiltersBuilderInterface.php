<?php


namespace Pfilsx\DataGrid\Grid;


use Doctrine\Common\Collections\Criteria;

interface DataGridFiltersBuilderInterface
{
    public function addEqualFilter(string $attribute, array $params) : self;

    public function addLikeFilter(string $attribute, array $params): self;

    public function addRelationFilter(string $attribute, string $relationClass, array $params): self;

    /**
     * @internal
     * @return Criteria
     */
    public function getCriteria(): Criteria;
}