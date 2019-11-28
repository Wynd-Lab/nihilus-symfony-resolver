<?php


namespace Nihilus\CQRSBundle\Tests\unit\Fake;

use Nihilus\CommandInterface;
use Nihilus\CQRSBundle\Command\CommandHandlerInterface;

class HelloCommandHandlerFake implements CommandHandlerInterface
{
    public function handle(CommandInterface $command): void
    {
        return;
    }

    public static function getHandledClass(): string
    {
        return 'HelloCommand';
    }
}