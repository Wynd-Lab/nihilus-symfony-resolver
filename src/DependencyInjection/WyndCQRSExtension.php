<?php

namespace Wynd\CQRSBundle\DependencyInjection;

use CQRSBundle\CQRS\Query\Resolver\ContainerQueryPipelineResolver;
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

        $container->setParameter('wynd.cqrs.query_pipelines_config', $config['pipelines']['query']);
        $container->setParameter('wynd.cqrs.command_pipelines_config', $config['pipelines']['command']);
    }
}
