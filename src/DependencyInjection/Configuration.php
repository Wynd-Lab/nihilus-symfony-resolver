<?php

namespace Wynd\CQRSBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Wynd\CQRSBundle\DependencyInjection
 *œœ
 * @codeCoverageIgnore
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('wynd_cqrs');

        $rootNode
            ->children()
                ->arrayNode('query')
                    ->children()
                        ->arrayNode('middlewares')
                            ->arrayPrototype()
                                ->scalarPrototype()->end()
                            ->end()
                        ->end()
                        ->arrayNode('binding')
                            ->arrayPrototype()
                                ->scalarPrototype()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('command')
                    ->children()
                        ->arrayNode('middlewares')
                            ->arrayPrototype()
                                ->scalarPrototype()->end()
                            ->end()
                        ->end()
                        ->arrayNode('binding')
                            ->arrayPrototype()
                                ->scalarPrototype()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
