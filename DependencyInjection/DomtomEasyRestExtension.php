<?php

namespace DomTomProject\EasyRestBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class DomtomEasyRestExtension extends Extension {

    public function load(array $configs, ContainerBuilder $container) {
        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('domtom_easy_rest.rules_directory', $config['rules_directory']);
        $container->setParameter('domtom_easy_rest.rules_parser_service', $config['rules_parser_service']);
        $container->setParameter('domtom_easy_rest.cacher_service', $config['cacher_service']);
        $container->setParameter('domtom_easy_rest.serializer_service', $config['serializer_service']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yml');
    }

}
