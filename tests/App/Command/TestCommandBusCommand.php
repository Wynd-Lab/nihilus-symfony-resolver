<?php

namespace  Nihilus\CQRSBundle\Tests\App\Command;

use Nihilus\CQRSBundle\Tests\App\CQRS\Command\TestCommand;
use Nihilus\CommandBusInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommandBusCommand extends Command
{
    protected static $defaultName = 'app:command';

    /**
     * @var CommandBusInteface
     */
    private $commandBus;

    /**
     * TestQueryBusCommand constructor.
     * @param CommandBusInteface $commandBus
     */
    public function __construct(CommandBusInterface $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }

    protected function configure()
    {
        $this->setDescription('Test the command bus command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        dump($this->commandBus->execute(new TestCommand()));
    }
}
