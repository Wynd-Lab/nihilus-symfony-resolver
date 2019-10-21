<?php

namespace Wynd\CQRSBundle\Tests\App\CQRS\Query\Pipeline;

use Nihilus\QueryInterface;
use Nihilus\QueryHandlerInterface;
use Nihilus\QueryPipelineInterface;

class Test1Pipeline implements QueryPipelineInterface
{
    public function handle(QueryInterface $query, QueryHandlerInterface $next): object
    {
        var_dump('Return another object lol');
        return $next->handle($query);
        return new \StdClass();
    }
}
