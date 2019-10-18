<?php


namespace Pfilsx\DataGrid;


use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class DataGridServiceContainer
 * @package Pfilsx\DataGrid
 * @internal
 */
class DataGridServiceContainer
{

    private $container;

    public function __construct(
        ManagerRegistry $doctrine,
        RouterInterface $router,
        Environment $twig,
        RequestStack $requestStack,
        TranslatorInterface $translator = null
    )
    {
        $this->container = [
            'doctrine' => $doctrine,
            'router' => $router,
            'twig' => $twig,
            'request' => $requestStack,
            'translator' => $translator
        ];
    }

    public function get(string $service)
    {
        return array_key_exists($service, $this->container) ? $this->container[$service] : null;
    }


    public function getDoctrine(): ManagerRegistry
    {
        return $this->container['doctrine'];
    }

    public function getRouter(): RouterInterface
    {
        return $this->container['router'];
    }

    public function getTwig(): Environment
    {
        return $this->container['twig'];
    }

    public function getRequest(): RequestStack
    {
        return $this->container['request'];
    }

    public function getTranslator(): ?TranslatorInterface
    {
        return $this->container['translator'];
    }
}