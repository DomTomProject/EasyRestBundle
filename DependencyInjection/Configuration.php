<?php

namespace DomTomProject\EasyRestBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {

    public function getConfigTreeBuilder(): TreeBuilder {
        $treeBuilder = new TreeBuilder();
        
        $rootNode = $treeBuilder->root('domtom_easy_rest');
        
        $rootNode
                ->children()
                    ->variableNode('rules_directory')->defaultValue("%kernel.root_dir%/Resources/Validation")->cannotBeEmpty()->end()
                    ->variableNode('rules_parser_service')->defaultValue('domtom_easy_rest.yaml_rules_parser')->cannotBeEmpty()->end()
                    ->variableNode('cacher_service')->defaultValue('domtom_easy_rest.cacher')->cannotBeEmpty()->end()
                    ->variableNode('serializer_service')->defaultValue('jms_serializer')->cannotBeEmpty()->end()
                    ->variableNode('custom_rules_namespace')->defaultValue('DomTomProject/EasyRestBundle/Rules')->cannotBeEmpty()->end()
                ->end()
            ->end();
                    
        return $treeBuilder;
    }

}
