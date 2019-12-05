<?php

namespace Nihilus\CQRSBundle\Command;

use Nihilus\CommandHandlerInterface as NihilusCommandHandlerInterface;

interface CommandHandlerInterface extends NihilusCommandHandlerInterface
{
    public static function getHandledClass(): string;
}
