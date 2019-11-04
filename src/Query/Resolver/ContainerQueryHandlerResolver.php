<?php

namespace Wynd\CQRSBundle\Query\Resolver;

use Nihilus\QueryHandlerInterface;
use Nihilus\QueryHandlerResolverInterface;
use Nihilus\QueryInterface;
use Psr\Container\ContainerInterface;

class ContainerQueryHandlerResolver implements QueryHandlerResolverInterface
{
    /**
     * @var array
     */
    private $queryHandlerMapping;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function addHandler(string $query, string $serviceName): void
    {
        $this->queryHandlerMapping[$query] = $serviceName;
    }

    public function getServiceId(string $query): ?string
    {
        return $this->queryHandlerMapping[$query] ?? null;
    }

    public function get(QueryInterface $query): ?QueryHandlerInterface
    {
        if (!isset($this->queryHandlerMapping[get_class($query)])) {
            return null;
        }
        if (!$this->container->has($this->queryHandlerMapping[get_class($query)])) {
            return null;
        }

        return $this->container->get($this->queryHandlerMapping[get_class($query)]);
    }
}
