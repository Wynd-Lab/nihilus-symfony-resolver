<?php

namespace Nihilus\CQRSBundle\Console;

use Nihilus\QueryHandlerResolverInterface;
use Nihilus\QueryMiddlewareResolverInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Nihilus\CQRSBundle\Command\Resolver\ContainerCommandHandlerResolver;
use Nihilus\CQRSBundle\Command\Resolver\ContainerCommandMiddlewareResolver;
use Nihilus\CQRSBundle\Query\Resolver\ContainerQueryHandlerResolver;
use Nihilus\CQRSBundle\Query\Resolver\ContainerQueryMiddlewareResolver;

class DebugCommand extends Command
{
    /**
     * @var array
     */
    private $queryNames;
    /**
     * @var QueryHandlerResolverInterface
     */
    private $queryHandlerResolver;
    /**
     * @var QueryMiddlewareResolverInterface
     */
    private $queryMiddlewareResolver;
    /**
     * @var ContainerCommandHandlerResolver
     */
    private $commandHandlerResolver;
    /**
     * @var ContainerCommandMiddlewareResolver
     */
    private $commandMiddlewareResolver;
    /**
     * @var array
     */
    private $commandNames;

    /**
     * DebugCommand constructor.
     * @param array $queryNames
     * @param ContainerQueryHandlerResolver $queryHandlerResolver
     * @param ContainerQueryMiddlewareResolver $queryMiddlewareResolver
     */
    public function __construct(
        array $queryNames,
        array $commandNames,
        ContainerQueryHandlerResolver $queryHandlerResolver,
        ContainerQueryMiddlewareResolver $queryMiddlewareResolver,
        ContainerCommandHandlerResolver $commandHandlerResolver,
        ContainerCommandMiddlewareResolver $commandMiddlewareResolver
    ) {
        parent::__construct();
        $this->queryNames = $queryNames;
        $this->commandNames = $commandNames;
        $this->queryHandlerResolver = $queryHandlerResolver;
        $this->queryMiddlewareResolver = $queryMiddlewareResolver;
        $this->commandHandlerResolver = $commandHandlerResolver;
        $this->commandMiddlewareResolver = $commandMiddlewareResolver;
    }

    protected function configure()
    {
        $this->setName('debug:cqrs:handler');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $outputSymfony = new SymfonyStyle($input, $output);

        $this->showQuery($outputSymfony);
        $this->showCommand($outputSymfony);
        return 0;
    }

    private function showQuery(SymfonyStyle $output): void
    {
        $output->section('Query registered');
        $informations = [];

        foreach ($this->queryNames as $queryName) {
            $informations[] = [
                $queryName,
                implode(', ', $this->queryMiddlewareResolver->getChain($queryName)),
                implode(', ', [$this->queryHandlerResolver->getServiceId($queryName)]),
            ];
        }

        $output->table(
            ['Class', 'Middleware(s)', 'Handler(s)'],
            $informations
        );
    }

    private function showCommand(SymfonyStyle $output): void
    {
        $output->section('Command registered');

        $informations = [];

        foreach ($this->commandNames as $queryName) {
            $informations[] = [
                $queryName,
                implode(', ', $this->commandMiddlewareResolver->getChain($queryName)),
                implode(', ', [$this->commandHandlerResolver->getServiceId($queryName)]),
            ];
        }

        $output->table(
            ['Class', 'Middleware(s)', 'Handler(s)'],
            $informations
        );
    }
}
