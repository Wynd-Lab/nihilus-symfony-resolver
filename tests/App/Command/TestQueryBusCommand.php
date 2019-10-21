<?php

namespace Wynd\CQRSBundle\Tests\App\Command;

use Wynd\CQRSBundle\Tests\App\CQRS\Query\TestQuery;
use Nihilus\QueryBusInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestQueryBusCommand extends Command
{
    protected static $defaultName = 'app:query';

    /**
     * @var QueryBusInterface
     */
    private $queryBus;

    /**
     * TestQueryBusCommand constructor.
     * @param QueryBusInterface $queryBus
     */
    public function __construct(QueryBusInterface $queryBus)
    {
        parent::__construct();
        $this->queryBus = $queryBus;
    }

    protected function configure()
    {
        $this->setDescription('Test the query bus command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        dump($this->queryBus->execute(new TestQuery()));
    }
}
