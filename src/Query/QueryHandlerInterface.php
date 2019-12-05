<?php


namespace Nihilus\CQRSBundle\Query;

use Nihilus\QueryHandlerInterface as NihilusQueryHandlerInterface;

interface QueryHandlerInterface extends NihilusQueryHandlerInterface
{
    public static function getHandledClass(): string;
}
