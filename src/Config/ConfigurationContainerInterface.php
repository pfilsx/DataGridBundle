<?php


namespace Pfilsx\DataGrid\Config;


interface ConfigurationContainerInterface
{
    public function __construct(array $config);

    public function getInstance(string $name): ConfigurationInterface;

}