<?php


namespace Pfilsx\DataGrid\Grid;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Pfilsx\DataGrid\Config\DataGridConfigurationInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

interface DataGridFactoryInterface
{
    public function __construct(ManagerRegistry $doctrine,
                                RouterInterface $router,
                                Environment $twig,
                                RequestStack $requestStack, DataGridConfigurationInterface $configs);

    public function createGrid(string $gridType, ServiceEntityRepository $repository): DataGrid;
}
