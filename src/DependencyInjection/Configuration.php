<?php


namespace Pfilsx\DataGrid\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface

{

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        if (\method_exists(TreeBuilder::class, 'getRootNode')) {
            $treeBuilder = new TreeBuilder('data_grid');
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root('data_grid');
        }
        $rootNode
            ->children()
            ->scalarNode('template')->defaultValue('@DataGrid/grid.blocks.html.twig')->end()
            ->scalarNode('noDataMessage')->defaultValue('No data found')->end()
            ->arrayNode('pagination')
            ->children()
            ->integerNode('limit')->defaultValue(10)->end()
            ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
