DataGridBuilder Class Reference
===============================

Usage
-----

.. code-block:: php

    namespace App\Grid;

    use Pfilsx\DataGrid\Grid\AbstractGridType;
    use Pfilsx\DataGrid\Grid\DataGridBuilderInterface;
    use Pfilsx\DataGrid\Grid\DataGridFiltersBuilderInterface;

    class EntityGridType extends AbstractGridType
    {
        ...
        public function handleFilters(DataGridFiltersBuilderInterface $builder, array $filters): void
        {
            $builder
                ->addEqualFilter('id')
                ->addLikeFilter('title')
        }
    }

Methods Reference
-----------------

addCustomFilter(string $attribute, callable $callback)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Adds custom filter handle function(``$callback``) for specific attribute.

.. block-code:: php

    $builder
        ->addCustomFilter('id', function(&$filtersCriteria, $attribute, $filterValue) {
            ...
        });

``$filtersCriteria`` - reference on Doctrine\Common\Collections\Criteria used for filters in grid.

addDateFilter(string $attribute, string $comparison = 'equal')
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Adds date filter handle for specific attribute.
``$comparison`` variants:
    - 'equal',
    - 'notEqual',
    - 'lt',
    - 'gt',
    - 'lte',
    - 'gte'

addEqualFilter(string $attribute)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Adds strong equal filter handle for specific attribute.

addLikeFilter(string $attribute)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Adds like filter handle for specific attribute('$attribute like %value%').

addRelationFilter(string $attribute, string $relatedEntityFQN)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Adds relation filter handle for specific attribute. Should be used with EntityFilter.


