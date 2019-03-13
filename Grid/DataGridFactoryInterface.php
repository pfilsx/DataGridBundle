<?php


namespace Pfilsx\DataGrid\Grid;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

interface DataGridFactoryInterface
{
    public function __construct(ContainerInterface $container, RequestStack $requestStack);

    public function createGrid(string $gridType, ServiceEntityRepository $repository): DataGrid;
}