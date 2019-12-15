<?php


namespace Pfilsx\DataGrid\Grid;


interface GridTypeInterface
{
    public function buildGrid(DataGridBuilderInterface $builder): void;

    public function handleFilters(DataGridFiltersBuilderInterface $builder, array $filters): void;
}