<?php

namespace Nihilus\CQRSBundle\Query\Resolver;

use Nihilus\QueryInterface;
use Nihilus\QueryMiddlewareResolverInterface;
use Psr\Container\ContainerInterface;

class ContainerQueryMiddlewareResolver implements QueryMiddlewareResolverInterface
{
    /**
     * @var array
     */
    private $middlewaresRegistered = [];

    /**
     * @var array
     */
    private $middlewaresChain = [];

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function addMiddleware(string $identifier, string $middlewareServiceId): void
    {
        $this->middlewaresRegistered[$identifier] = $middlewareServiceId;
    }

    public function addChain(string $name, array $config): void
    {
        $this->middlewaresChain[$name] = $config;
    }

    public function getChain(string $name): array
    {
        return $this->middlewaresChain[$name];
    }

    public function get(QueryInterface $query): array
    {
        $middlewaresResolved = [];

        $chainMiddlewares = $this->middlewaresChain[get_class($query)] ?? [];

        foreach ($chainMiddlewares as $identifier) {
            $middlewareServiceId = $this->middlewaresRegistered[$identifier];
            $middlewaresResolved[] = $this->container->get($middlewareServiceId);
        }

        return $middlewaresResolved;
    }
}
