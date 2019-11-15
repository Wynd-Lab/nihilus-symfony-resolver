<?php

namespace Nihilus\CQRSBundle;

use Nihilus\CommandMiddlewareResolverInterface;
use Nihilus\CQRSBundle\Builder\MiddlewareChainBuilder;
use Nihilus\CQRSBundle\Command\Resolver\ContainerCommandHandlerResolver;
use Nihilus\CQRSBundle\DependencyInjection\CompilerPass\AutoRegisterChainMiddlewareCompilerPass;
use Nihilus\CQRSBundle\Query\Resolver\ContainerQueryHandlerResolver;
use Nihilus\CQRSBundle\DependencyInjection\CompilerPass\AutoRegisterMessageHandlerCompilerPass;
use Nihilus\CQRSBundle\DependencyInjection\CompilerPass\AutoRegisterMiddlewareCompilerPass;
use Nihilus\QueryMiddlewareResolverInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class NihilusCQRSBundle
 * @package Wynd\CQRSBundle
 *
 * @codeCoverageIgnore
 */
class NihilusCQRSBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AutoRegisterMessageHandlerCompilerPass(
            ContainerQueryHandlerResolver::class,
            'nihilus.cqrs.query_handler'
        ));
        $container->addCompilerPass(new AutoRegisterMessageHandlerCompilerPass(
            ContainerCommandHandlerResolver::class,
            'nihilus.cqrs.command_handler'
        ));

        $container->addCompilerPass(new AutoRegisterChainMiddlewareCompilerPass(
            QueryMiddlewareResolverInterface::class,
            'nihilus.cqrs.query_handler',
            'query',
            new MiddlewareChainBuilder()
        ));
        $container->addCompilerPass(new AutoRegisterChainMiddlewareCompilerPass(
            CommandMiddlewareResolverInterface::class,
            'nihilus.cqrs.command_handler',
            'command',
            new MiddlewareChainBuilder()
        ));

        $container->addCompilerPass(new AutoRegisterMiddlewareCompilerPass(
            QueryMiddlewareResolverInterface::class,
            'nihilus.cqrs.query_middleware'
        ));
        $container->addCompilerPass(new AutoRegisterMiddlewareCompilerPass(
            CommandMiddlewareResolverInterface::class,
            'nihilus.cqrs.command_middleware'
        ));

        parent::build($container);
    }
}
