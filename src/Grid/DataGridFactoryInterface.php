<?php


namespace Pfilsx\DataGrid\Grid;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Pfilsx\DataGrid\Config\DataGridConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

interface DataGridFactoryInterface
{
    public function __construct(ContainerInterface $container, RequestStack $requestStack, DataGridConfigurationInterface $configs);

    public function createGrid(string $gridType, ServiceEntityRepository $repository): DataGrid;
}
