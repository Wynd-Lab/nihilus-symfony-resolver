<?php

namespace Nihilus\CQRSBundle\Tests\App\CQRS\Command\Handler;

use Nihilus\CommandHandlerInterface;
use Nihilus\CommandInterface;

class TestCommandHandler implements CommandHandlerInterface
{
    public function handle(CommandInterface $command): void
    {
        dump('oui je suis le maitre du monde');
    }
}