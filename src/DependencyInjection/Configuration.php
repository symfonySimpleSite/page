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
                ->arrayNode('template')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('default')->defaultTrue()->defaultValue('base.html.twig')->end()
                        ->scalarNode('site_index')->defaultTrue()->defaultValue('base.html.twig')->end()
                    ->end()
                ->end() // twitter
            ->end();
        return $treeBuilder;
    }
}
