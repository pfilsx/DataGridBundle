<?php


namespace Pfilsx\DataGrid\Grid;


use Pfilsx\DataGrid\Config\ConfigurationContainerInterface;
use Pfilsx\DataGrid\DataGridServiceContainer;

interface DataGridFactoryInterface
{
    public function __construct(DataGridServiceContainer $container, ConfigurationContainerInterface $configs);

    public function createGrid(string $gridType, $dataSource): DataGridInterface;
}
