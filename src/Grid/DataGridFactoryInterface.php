<?php


namespace Pfilsx\DataGrid\Grid;


use Pfilsx\DataGrid\Config\DataGridConfigurationInterface;
use Pfilsx\DataGrid\DataGridServiceContainer;

interface DataGridFactoryInterface
{
    public function __construct(DataGridServiceContainer $container, DataGridConfigurationInterface $configs);

    public function createGrid(string $gridType, $dataProvider): DataGrid;
}
