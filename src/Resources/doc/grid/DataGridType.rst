DataGridType Class Reference
============================

Usage
-----

You must create your own DataGridType for specific entity. Your type must extend AbstractGridType.

.. code-block:: php

    namespace App\Grid;

    use Pfilsx\DataGrid\Grid\AbstractGridType;
    use Pfilsx\DataGrid\Grid\DataGridBuilderInterface;
    use Pfilsx\DataGrid\Grid\DataGridFiltersBuilderInterface;

    class EntityGridType extends AbstractGridType
    {

        public function buildGrid(DataGridBuilderInterface $builder) : void
        {
            $builder
                ->addDataColumn('id', [
                    'filter' => [
                        'class' => self::FILTER_TEXT
                    ]
                ])
                ->addColumn('title', self::DATA_COLUMN, [
                    'filter' => [
                        'class' => self::FILTER_TEXT
                    ],
                ])
                ->addActionColumn([
                    'pathPrefix' => 'entity'
                ]);
        }

        public function handleFilters(DataGridFiltersBuilderInterface $builder, array $filters): void
        {
            $builder
                ->addEqualFilter('id')
                ->addLikeFilter('title')
        }
    }

.. toctree::

    DataGridBuilder
    DataGridFiltersBuilder
