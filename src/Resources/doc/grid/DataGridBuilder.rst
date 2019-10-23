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
                ->addColumn('title', self::DATA_COLUMN, [
                    'filter' => [
                        'class' => self::FILTER_TEXT
                    ],
                ])
                ->addActionColumn([
                    'pathPrefix' => 'entity'
                ]);
        }
        ...
    }

Methods Reference
-----------------

addColumn(string $attribute, string $columnFQN, array $columnOptions = [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Adds column of specific type to grid. See columns reference for ``$columnOptions`` and listing of default columns.

addDataColumn(string $attribute, array $columnOptions = [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Adds DataColumn to grid. Similar to ``addColumn($attribute, self::DATA_COLUMN, $columnOptions)``

addActionColumn(array $columnOptions = [])
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Adds DataColumn to grid. Similar to ``addColumn('', self::ACTION_COLUMN, $columnOptions)``

enablePagination(boolean $isEnabled, int $limit = 10)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Configures grid pagination options or disable pagination. Only one option available right now - ``limit``.
``limit`` - max count of rows on each page.

setTemplate(string $path)
~~~~~~~~~~~~~~~~~~~~~~~~~
Overrides default grid template.

setNoDataMessage(string $message)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Overrides default empty data message.
