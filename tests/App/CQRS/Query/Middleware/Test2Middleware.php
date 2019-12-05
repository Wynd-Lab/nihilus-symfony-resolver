<?php

declare(strict_types=1);

namespace Nihilus\CQRSBundle\Tests\App\CQRS\Query\Middleware;

use Nihilus\QueryInterface;
use Nihilus\QueryHandlerInterface;
use Nihilus\QueryMiddlewareInterface;

class Test2Middleware implements QueryMiddlewareInterface
{
    public function handle(QueryInterface $query, QueryHandlerInterface $next): object
    {
        var_dump('Before 2');
        $r = $next->handle($query);
        var_dump('After 2');

        return $r;
    }
}
