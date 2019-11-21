<?php


namespace Pfilsx\DataGrid\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class GridPass implements CompilerPassInterface
{
    private $gridExtensionService;
    private $gridTypeTag;

    public function __construct(string $gridExtensionService = 'data_grid.extension', string $gridTypeTag = 'data_grid.type')
    {
        $this->gridExtensionService = $gridExtensionService;
        $this->gridTypeTag = $gridTypeTag;
    }

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->gridExtensionService)) {
            return;
        }

        $definition = $container->getDefinition($this->gridExtensionService);
        $definition->replaceArgument(0, $this->processGridTypes($container));
    }

    private function processGridTypes(ContainerBuilder $container)
    {
        // Get service locator argument
        $servicesMap = [];
        $namespaces = [];

        // Builds an array with fully-qualified type class names as keys and service IDs as values
        foreach ($container->findTaggedServiceIds($this->gridTypeTag, true) as $serviceId => $tag) {
            // Add form type service to the service locator
            $serviceDefinition = $container->getDefinition($serviceId);
            $servicesMap[$formType = $serviceDefinition->getClass()] = new Reference($serviceId);
            $namespaces[substr($formType, 0, strrpos($formType, '\\'))] = true;
        }

        return ServiceLocatorTagPass::register($container, $servicesMap);
    }
}