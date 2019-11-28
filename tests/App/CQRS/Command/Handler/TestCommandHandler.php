<?php

declare(strict_types=1);

namespace Nihilus\CQRSBundle\Tests\App\CQRS\Command\Handler;

use Nihilus\CQRSBundle\Command\CommandHandlerInterface;
use Nihilus\CommandInterface;
use Nihilus\CQRSBundle\Tests\App\CQRS\Command\TestCommand;

class TestCommandHandler implements CommandHandlerInterface
{
    public function handle(CommandInterface $command): void
    {
        dump('oui je suis le maitre du monde');
    }

    public static function getHandledClass(): string
    {
        return TestCommand::class;
    }
}