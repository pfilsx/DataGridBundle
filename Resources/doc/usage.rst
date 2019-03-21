Usage
=====

Step 1: Create data entity
--------------------------

.. code-block:: bash

    $ php bin/console make:entity ...

Step 2: Create data grid type for entity
----------------------------------------

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
                ->addColumn(self::BOOLEAN_COLUMN, [
                    'attribute' => 'is_enabled',
                    'format' => 'html',
                    'trueValue' => '<i class="fas fa-check"></i>',
                    'falseValue' => '<i class="fas fa-times"></i>',
                    'label' => 'Activity flag',
                    'filter' => [
                        'class' => self::FILTER_BOOLEAN
                    ]
                ])
                ->addColumn(self::ACTION_COLUMN, [
                    'pathPrefix' => 'entity'
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
            'dataGrid' => $grid
        ]);
    }

Step 4: Display grid in your twig template
------------------------------------------

.. code-block:: twig

    {{ grid_view(dataGrid, {class: 'table'}) }}


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