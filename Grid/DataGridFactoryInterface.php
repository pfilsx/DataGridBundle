<?php


namespace Pfilsx\DataGrid\Grid;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\HttpFoundation\Request;

interface DataGridFactoryInterface
{
    public function createGrid(string $gridType, ServiceEntityRepository $repository): self;

    public function handleRequest(Request $request): self;

    public function getGrid():DataGrid;
}