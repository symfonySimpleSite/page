<?php

namespace SymfonySimpleSite\Page\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use SymfonySimpleSite\Page\PageBundle;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(PageBundle::getConfigName());

        $treeBuilder->getRootNode()
            ->children()
            ->scalarNode('some_value')->defaultValue('some value 11')->end()
            ->end();

        return $treeBuilder;
    }
}
