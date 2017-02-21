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
                ->end()
            ->end();
                    
        return $treeBuilder;
    }

}
