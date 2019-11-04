<?php

namespace Wynd\CQRSBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * Class WyndCQRSExtension
 * @package Wynd\CQRSBundle\DependencyInjection
 *
 * @codeCoverageIgnore
 */
class WyndCQRSExtension extends ConfigurableExtension
{
    public function loadInternal(array $config, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('services.yml');

        $container->setParameter('wynd.cqrs.query_middlewares_config', $config['query']['middlewares']);
        $container->setParameter('wynd.cqrs.command_middlewares_config', $config['command']['middlewares']);
        $container->setParameter('wynd.cqrs.query_middlewares_binding', $config['query']['binding']);
        $container->setParameter('wynd.cqrs.command_middlewares_binding', $config['command']['binding']);
    }
}
