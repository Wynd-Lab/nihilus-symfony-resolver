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
    const CHAINS_OF_MIDDLEWARE = 'middleware_chains';
    const BINDING_OF_HANDLERS = 'binding';

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('nihilus_cqrs');

        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('query')
                    ->children()
                        ->arrayNode(self::CHAINS_OF_MIDDLEWARE)
                            ->info('A list of named chains of middlewares. You can have none, one or many middlewares in each chain.')
                            ->arrayPrototype()
                                ->scalarPrototype()->end()
                            ->end()
                        ->end()
                        ->arrayNode(self::BINDING_OF_HANDLERS)
                            ->info('anonymous chain bind to handle, global chain used by default is ommit')
                            ->arrayPrototype()
                                ->scalarPrototype()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('command')
                    ->children()
                        ->arrayNode(self::CHAINS_OF_MIDDLEWARE)
                            ->info('A list of named chains of middlewares. You can have none, one or many middlewares in each chain.')
                            ->arrayPrototype()
                                ->scalarPrototype()->end()
                            ->end()
                        ->end()
                        ->arrayNode(self::BINDING_OF_HANDLERS)
                            ->info('anonymous chain bind to handle, global chain used by default is ommit')
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
