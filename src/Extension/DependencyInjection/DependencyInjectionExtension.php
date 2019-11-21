<?php


namespace Pfilsx\DataGrid\Extension\DependencyInjection;


use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DependencyInjectionExtension
{
    /**
     * @var ContainerInterface
     */
    private $typeContainer;

    public function __construct($typeContainer)
    {
        $this->typeContainer = $typeContainer;
    }

    public function getType($name)
    {
        if (!$this->typeContainer->has($name)) {
            throw new InvalidArgumentException(sprintf('The field type "%s" is not registered in the service container.', $name));
        }

        return $this->typeContainer->get($name);
    }

    public function hasType($name)
    {
        return $this->typeContainer->has($name);
    }
}