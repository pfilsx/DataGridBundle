<?php


namespace Pfilsx\DataGrid\Grid;


use Pfilsx\DataGrid\Config\ConfigurationContainerInterface;
use Pfilsx\DataGrid\DataGridServiceContainer;
use Pfilsx\DataGrid\Extension\DependencyInjection\DependencyInjectionExtension;

interface DataGridFactoryInterface
{
    public function __construct(DataGridServiceContainer $container, ConfigurationContainerInterface $configs, DependencyInjectionExtension $extension);

    public function createGrid(string $gridType, $dataSource, array $params = []): DataGridInterface;
}
