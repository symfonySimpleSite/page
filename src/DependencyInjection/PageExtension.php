<?php

namespace SymfonySimpleSite\Page\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use SymfonySimpleSite\Page\PageBundle;

class PageExtension extends Extension
{
    public function prepend(ContainerBuilder $container): void
    {
/*
        $container->prependExtensionConfig(SymfonySimpleSitePageBundle::getConfigName(), [
            'images_path' => [
                [
                    'dir' => 'articles',
                      'small' => [
                          'width' => 120,
                          'height' => 120,
                          'path' => 'small'
                      ]
                ]
            ]
        ]);*/
    }


    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter(PageBundle::getConfigName(), $config);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(dirname(__DIR__) . '/Resources/config')
        );
        $loader->load('services.yaml');
    }
}