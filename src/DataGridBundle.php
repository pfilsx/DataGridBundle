<?php


namespace Pfilsx\DataGrid;


use Pfilsx\DataGrid\DependencyInjection\Compiler\GridPass;
use Pfilsx\DataGrid\Grid\GridTypeInterface;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DataGridBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new GridPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 0);
        $container->registerForAutoconfiguration(GridTypeInterface::class)->addTag('data_grid.type');
    }
}
