<?php

declare(strict_types=1);

namespace Nihilus\CQRSBundle\Tests\App\CQRS\Query\Handler;

use Nihilus\CQRSBundle\Query\QueryHandlerInterface;
use Nihilus\CQRSBundle\Tests\App\CQRS\Query\TestQuery;
use Nihilus\QueryInterface;

class TestQueryHandler implements QueryHandlerInterface
{
    public function handle(QueryInterface $query)
    {
        return new class()
        {
            public $property = 'property';
        };
    }

    public static function getHandledClass(): string
    {
        return TestQuery::class;
    }
}
