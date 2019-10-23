Usage
=====

Step 1: Create data entity
--------------------------

.. code-block:: bash

    $ php bin/console make:entity ...

Step 2: Create data grid type for entity
----------------------------------------

.. code-block:: bash

    $ php bin/console make:grid

Command will generate basic GridType code for you. Next step you can customize it:

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
                ->addColumn('is_enabled', self::BOOLEAN_COLUMN, [
                    'format' => 'html',
                    'trueValue' => '<i class="fas fa-check"></i>',
                    'falseValue' => '<i class="fas fa-times"></i>',
                    'label' => 'Activity flag',
                    'filter' => [
                        'class' => self::FILTER_BOOLEAN
                    ]
                ])
                ->addActionColumn([
                    'pathPrefix' => 'entity_action'
                ]);
        }

        public function handleFilters(DataGridFiltersBuilderInterface $builder, array $filters): void
        {
            $builder
                ->addEqualFilter('id')
                ->addLikeFilter('title')
                ->addEqualFilter('is_enabled');
        }
    }

Step 3: Create grid in your controller
----------------------------------------

.. code-block:: php

    public function index(EntityRepository $entityRepository, DataGridFactoryInterface $factory): Response
    {
        $grid = $factory->createGrid(EntityGridType::class, $entityRepository);
        return $this->render('entity/index.html.twig', [
            'grid' => $grid->createView()
        ]);
    }

Step 4: Display grid in your twig template
------------------------------------------

.. code-block:: twig

    {{ grid_view(grid, {class: 'table'}) }}

Additional step: Register assets for automating sorting and filtering
----------------
You need to include ``datagrid.min.css`` and ``datagrid.js`` from ``public/bundles/datagrid/`` for automatic
filters and sorting

.. block-code:: bash

    $ php bin/console assets:install

.. block-code:: html

    <link rel="stylesheet" href="public/bundles/datagrid/datagrid.min.css">
    <script src="public/bundles/datagrid/datagrid.js"></script>


DataGridType reference
----------------------
.. toctree::

    grid/DataGridType


DataGridType columns reference
------------------------------
.. toctree::

    columns/ActionColumn
    columns/BooleanColumn
    columns/DataColumn
    columns/DateColumn
    columns/ImageColumn
    columns/SerialColumn

DataGridType filters reference
------------------------------
.. toctree::

    filters/BooleanFilter
    filters/ChoiceFilter
    filters/CustomFilter
    filters/DateFilter
    filters/EntityFilter
    filters/TextFilter
