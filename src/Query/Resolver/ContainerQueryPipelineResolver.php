<?php

namespace Wynd\CQRSBundle\Query\Resolver;

use Nihilus\QueryPipelineResolverInterface;
use Psr\Container\ContainerInterface;

class ContainerQueryPipelineResolver implements QueryPipelineResolverInterface
{
    /**
     * @var array
     */
    private $pipelinesRegistered = [];

    private $pipelinesConfig = [];

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container, array $pipelinesConfig)
    {
        $this->container = $container;
        $this->pipelinesConfig = $pipelinesConfig;
    }

    public function addPipeline(string $identifier, string $pipelineServiceId)
    {
        $this->pipelinesRegistered[$identifier] = $pipelineServiceId;
    }

    public function getGlobals(): array
    {
        $pipelinesResolved = [];

        foreach ($this->pipelinesConfig['global'] as $identifier) {
            $pipelineServiceId = $this->pipelinesRegistered[$identifier];
            $pipelinesResolved[] = $this->container->get($pipelineServiceId);
        }

        return $pipelinesResolved;
    }
}
