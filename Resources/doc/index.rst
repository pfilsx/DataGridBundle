Getting started with DataGridBundle
========================================

Overview
--------

The bundle integrates DataGrid tables into `Symfony`_ . It
automatically registers a new DataGridFactory which can be fully
configured.

Here, an example where we create the DataGrid instance::

    use Pfilsx\DataGrid\Grid\DataGridFactoryInterface;

    public function index(EntityRepository $entityRepository, DataGridFactoryInterface $factory): Response
    {
        $grid = $factory->createGrid(EntityGridType::class, $entityRepository);
        return $this->render('entity/index.html.twig', [
            'dataGrid' => $grid
        ]);
    }

.. toctree::

    installation
    configuration
    usage

.. _`Symfony`: http://symfony.com/