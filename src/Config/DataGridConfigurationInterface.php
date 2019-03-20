<?php


namespace Pfilsx\DataGrid\Config;


interface DataGridConfigurationInterface
{
    public function getTemplate(): string;

    public function getNoDataMessage(): string;

    public function getPagination(): array;

    public function getConfigs(): array;
}