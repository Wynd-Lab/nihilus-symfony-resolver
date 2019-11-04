<?php

namespace Wynd\CQRSBundle;

use Nihilus\CommandMiddlewareResolverInterface;
use Wynd\CQRSBundle\Builder\MiddlewareChainBuilder;
use Wynd\CQRSBundle\Command\Resolver\ContainerCommandHandlerResolver;
use Wynd\CQRSBundle\DependencyInjection\CompilerPass\AutoRegisterChainMiddlewareCompilerPass;
use Wynd\CQRSBundle\Query\Resolver\ContainerQueryHandlerResolver;
use Wynd\CQRSBundle\DependencyInjection\CompilerPass\AutoRegisterMessageHandlerCompilerPass;
use Wynd\CQRSBundle\DependencyInjection\CompilerPass\AutoRegisterMiddlewareCompilerPass;
use Nihilus\QueryMiddlewareResolverInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class WyndCQRSBundle
 * @package Wynd\CQRSBundle
 *
 * @codeCoverageIgnore
 */
class WyndCQRSBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AutoRegisterMessageHandlerCompilerPass(
            ContainerQueryHandlerResolver::class,
            'wynd.cqrs.query_handler'
        ));
        $container->addCompilerPass(new AutoRegisterMessageHandlerCompilerPass(
            ContainerCommandHandlerResolver::class,
            'wynd.cqrs.command_handler'
        ));

        $container->addCompilerPass(new AutoRegisterChainMiddlewareCompilerPass(
            QueryMiddlewareResolverInterface::class,
            'wynd.cqrs.query_handler',
            'query',
            new MiddlewareChainBuilder()
        ));
        $container->addCompilerPass(new AutoRegisterChainMiddlewareCompilerPass(
            CommandMiddlewareResolverInterface::class,
            'wynd.cqrs.command_handler',
            'command',
            new MiddlewareChainBuilder()
        ));

        $container->addCompilerPass(new AutoRegisterMiddlewareCompilerPass(
            QueryMiddlewareResolverInterface::class,
            'wynd.cqrs.query_middleware'
        ));
        $container->addCompilerPass(new AutoRegisterMiddlewareCompilerPass(
            CommandMiddlewareResolverInterface::class,
            'wynd.cqrs.command_middleware'
        ));

        parent::build($container);
    }
}
