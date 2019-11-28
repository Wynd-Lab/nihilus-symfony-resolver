<?php

declare(strict_types=1);

namespace Nihilus\CQRSBundle\Tests\App\CQRS\Query\Middleware;

use Nihilus\QueryInterface;
use Nihilus\QueryHandlerInterface;
use Nihilus\QueryMiddlewareInterface;

class Test3Middleware implements QueryMiddlewareInterface
{
    public function handle(QueryInterface $query, QueryHandlerInterface $next): object
    {
        var_dump('Before 3');
        $r = $next->handle($query);
        var_dump('After 3');

        return $r;
    }
}
