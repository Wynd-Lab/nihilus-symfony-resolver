<?php

namespace Nihilus\CQRSBundle\DependencyInjection;

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

        $container->setParameter('Nihilus\CQRSBundle\.cqrs.query_middlewares_config', $config['query']['middlewares']);
        $container->setParameter('Nihilus\CQRSBundle\.cqrs.command_middlewares_config', $config['command']['middlewares']);
        $container->setParameter('Nihilus\CQRSBundle\.cqrs.query_middlewares_binding', $config['query']['binding']);
        $container->setParameter('Nihilus\CQRSBundle\.cqrs.command_middlewares_binding', $config['command']['binding']);
    }
}
