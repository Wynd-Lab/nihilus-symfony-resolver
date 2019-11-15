<?php

namespace Nihilus\CQRSBundle\Tests\App\CQRS\Query\Middleware;

use Nihilus\QueryInterface;
use Nihilus\QueryHandlerInterface;
use Nihilus\QueryMiddlewareInterface;

class Test1Middleware implements QueryMiddlewareInterface
{
    public function handle(QueryInterface $query, QueryHandlerInterface $next): object
    {
        var_dump('Return another object lol');
        return $next->handle($query);
        return new \StdClass();
    }
}
