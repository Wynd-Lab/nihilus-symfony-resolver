<?php

namespace Wynd\CQRSBundle\Command\Resolver;

use Nihilus\CommandInterface;
use Nihilus\CommandMiddlewareResolverInterface;
use Psr\Container\ContainerInterface;

class ContainerCommandMiddlewareResolver implements CommandMiddlewareResolverInterface
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

    public function addMiddleware(string $identifier, string $middlewareServiceId)
    {
        $this->middlewaresRegistered[$identifier] = $middlewareServiceId;
    }

    public function addChain(string $name, array $config)
    {
        $this->middlewaresChain[$name] = $config;
    }

    public function getChain(string $name)
    {
        return $this->middlewaresChain[$name];
    }

    public function get(CommandInterface $command): array
    {
        $middlewaresResolved = [];

        $chainMiddlewares = $this->middlewaresChain[get_class($command)] ?? [];

        foreach ($chainMiddlewares as $identifier) {
            $middlewareServiceId = $this->middlewaresRegistered[$identifier];
            $middlewaresResolved[] = $this->container->get($middlewareServiceId);
        }

        return $middlewaresResolved;
    }
}
