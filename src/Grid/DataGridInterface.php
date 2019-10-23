<?php


namespace Pfilsx\DataGrid\Grid;


use Pfilsx\DataGrid\Config\ConfigurationContainerInterface;
use Pfilsx\DataGrid\DataGridServiceContainer;
use Pfilsx\DataGrid\Grid\Providers\DataProviderInterface;

interface DataGridInterface
{
    public function __construct(AbstractGridType $type,
                                DataProviderInterface $dataProvider,
                                ConfigurationContainerInterface $defaultConfiguration,
                                DataGridServiceContainer $container);

    public function createView(): DataGridView;
}