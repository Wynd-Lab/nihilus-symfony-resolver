<?php

namespace Nihilus\CQRSBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Nihilus\CQRSBundle\DependencyInjection
 *œœ
 * @codeCoverageIgnore
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('nihilus_cqrs');

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
