<?php

namespace Wynd\CQRSBundle;

use Nihilus\CommandPipelineResolverInterface;
use Wynd\CQRSBundle\Command\Resolver\ContainerCommandHandlerResolver;
use Wynd\CQRSBundle\Query\Resolver\ContainerQueryHandlerResolver;
use Wynd\CQRSBundle\DependencyInjection\CompilerPass\AutoRegisterMessageHandlerCompilerPass;
use Wynd\CQRSBundle\DependencyInjection\CompilerPass\AutoRegisterPipelineCompilerPass;
use Nihilus\QueryPipelineResolverInterface;
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
        $container->addCompilerPass(new AutoRegisterPipelineCompilerPass(
            QueryPipelineResolverInterface::class,
            'wynd.cqrs.query_pipeline'
        ));
        $container->addCompilerPass(new AutoRegisterPipelineCompilerPass(
            CommandPipelineResolverInterface::class,
            'wynd.cqrs.command_pipeline'
        ));

        parent::build($container);
    }
}
