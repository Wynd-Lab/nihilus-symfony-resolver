<?php

namespace Wynd\CQRSBundle\Tests\App\CQRS\Query\Handler;

use Nihilus\QueryHandlerInterface;
use Nihilus\QueryInterface;

class TestQueryHandler implements QueryHandlerInterface
{
    public function handle(QueryInterface $query): object
    {
        return new class()
        {
            public $property = 'property';
        };
    }
}
