<?php


namespace Pfilsx\DataGrid\Grid;


use Pfilsx\DataGrid\Config\ConfigurationContainerInterface;
use Pfilsx\DataGrid\DataGridServiceContainer;

interface DataGridInterface
{
    public function __construct(DataGridBuilderInterface $builder,
                                ConfigurationContainerInterface $defaultConfiguration,
                                DataGridServiceContainer $container);

    public function createView(): DataGridView;
}