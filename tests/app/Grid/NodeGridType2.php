<?php


namespace Pfilsx\tests\app\Grid;


use Pfilsx\DataGrid\Grid\AbstractGridType;
use Pfilsx\DataGrid\Grid\DataGridBuilderInterface;
use Pfilsx\DataGrid\Grid\DataGridFiltersBuilderInterface;

class NodeGridType2 extends AbstractGridType
{

    public function buildGrid(DataGridBuilderInterface $builder): void
    {
        $builder
            ->addColumn('id')
            ->addColumn('user')
            ->addColumn('content')
            ->addColumn('createdAt', self::DATE_COLUMN, [
                'dateFormat' => 'Y.m.d'
            ])
            ->addDataColumn('parentId')
            ->addActionColumn([
                'prefix' => 'test_prefix_'
            ])
            ->setTranslationDomain('test')
            ->enablePagination(false);
    }

    public function handleFilters(DataGridFiltersBuilderInterface $builder, array $filters): void
    {

    }
}