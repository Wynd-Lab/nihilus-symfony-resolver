<?php

namespace Nihilus\CQRSBundle\Command\Resolver;

use Nihilus\CommandHandlerInterface;
use Nihilus\CommandHandlerResolverInterface;
use Nihilus\CommandInterface;
use Psr\Container\ContainerInterface;

class ContainerCommandHandlerResolver implements CommandHandlerResolverInterface
{
    /**
     * @var array
     */
    private $handlerMapping;

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
        $this->handlerMapping[$query] = $serviceName;
    }

    public function get(CommandInterface $command): ?CommandHandlerInterface
    {
        if (!isset($this->handlerMapping[get_class($command)])) {
            return null;
        }
        if (!$this->container->has($this->handlerMapping[get_class($command)])) {
            return null;
        }

        return $this->container->get($this->handlerMapping[get_class($command)]);
    }

    public function getServiceId(string $query): ?string
    {
        return $this->handlerMapping[$query] ?? null;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @param CommandInterface $command
     * @return array
     */
    public function getAll(CommandInterface $command): array
    {
        return [];
    }
}
