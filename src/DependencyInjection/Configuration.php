<?php

namespace CodedMonkey\Conductor\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('conductor');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode->children()
            ->scalarNode('title')->defaultValue('My Conductor')->end()
            ->arrayNode('storage')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('path')->defaultValue('%kernel.project_dir%/storage')->end()
                ->end()
            ->end()
            ->arrayNode('repositories')
                ->useAttributeAsKey('name')
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('url')->isRequired()->end()
                        ->scalarNode('type')->defaultValue('composer')->end()
                        ->integerNode('delay')->defaultValue(3600)->end()
                        ->arrayNode('auth')
                            ->requiresAtLeastOneElement()
                            ->scalarPrototype()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}