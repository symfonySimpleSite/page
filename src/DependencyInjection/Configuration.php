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
                ->arrayNode('image')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('save_path')->defaultTrue()->defaultValue('/__ROOT__/public/upload/page')->end()
                        ->scalarNode('url_path')->defaultTrue()->defaultValue('/upload/page')->end()
                        ->scalarNode('big_size')->defaultTrue()->defaultValue('600x600')->end()
                        ->scalarNode('small_size')->defaultTrue()->defaultValue('2500x250')->end()
                        ->booleanNode('is_delete_origin')->defaultTrue()->defaultValue(true)->end()
                    ->end()
                ->end()
                ->arrayNode('template')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('default')->defaultTrue()->defaultValue('base.html.twig')->end()
                        ->scalarNode('site_index')->defaultTrue()->defaultValue('base.html.twig')->end()
                    ->end()
                ->end()
            ->end();
        return $treeBuilder;
    }
}
