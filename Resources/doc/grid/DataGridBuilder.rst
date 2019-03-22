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

        public function buildGrid(DataGridBuilderInterface $builder) : void
        {
            $builder
                ->addDataColumn('id', [
                    'filter' => [
                        'class' => self::FILTER_TEXT
                    ]
                ])
                ->addColumn(self::DATA_COLUMN, [
                    'attribute' => 'title',
                    'filter' => [
                        'class' => self::FILTER_TEXT
                    ],
                ])
                ->addColumn(self::ACTION_COLUMN, [
                    'pathPrefix' => 'entity'
                ]);
        }
        ...
    }

Methods Reference
-----------------

addColumn(string $columnFQN, array $columnOptions = [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Adds column of specific type to grid. See columns reference for ``$columnOptions`` and listing of default columns.

addDataColumn(string $attribute, array $columnOptions = [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Adds DataColumn to grid. Similar to ``addColumn(self::DATA_COLUMN, ['attribute' => ''])``

enablePagination(array $options = [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Configures grid pagination options. Only one option available right now - ``limit``.
``limit`` - max count of rows on each page.

setTemplate(string $path)
~~~~~~~~~~~~~~~~~~~~~~~~~
Overrides default grid template.

setNoDataMessage(string $message)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Overrides default empty data message.