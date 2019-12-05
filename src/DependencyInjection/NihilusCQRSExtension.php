<?php

namespace Nihilus\CQRSBundle\DependencyInjection;

use Nihilus\CommandMiddlewareInterface;
use Nihilus\CQRSBundle\Command\CommandHandlerInterface;
use Nihilus\CQRSBundle\Query\QueryHandlerInterface;
use Nihilus\QueryMiddlewareInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * Class NihilusCQRSExtension
 * @package Nihilus\CQRSBundle\DependencyInjection
 *
 * @codeCoverageIgnore
 */
class NihilusCQRSExtension extends ConfigurableExtension
{
    public function loadInternal(array $config, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('services.yml');


        $container->registerForAutoconfiguration(QueryHandlerInterface::class)
            ->addTag('nihilus.cqrs.query_handler');
        $container->registerForAutoconfiguration(CommandHandlerInterface::class)
            ->addTag('nihilus.cqrs.command_handler');

        $container->setParameter('nihilus.cqrs.query_middlewares_config', $config['query']['middleware_chains']);
        $container->setParameter('nihilus.cqrs.command_middlewares_config', $config['command']['middleware_chains']);
        $container->setParameter('nihilus.cqrs.query_middlewares_binding', $config['query']['binding']);
        $container->setParameter('nihilus.cqrs.command_middlewares_binding', $config['command']['binding']);
    }
}
