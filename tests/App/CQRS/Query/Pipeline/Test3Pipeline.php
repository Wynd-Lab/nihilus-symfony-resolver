<?php

namespace Wynd\CQRSBundle\Tests\App\CQRS\Query\Pipeline;

use Nihilus\QueryInterface;
use Nihilus\QueryHandlerInterface;
use Nihilus\QueryPipelineInterface;

class Test3Pipeline implements QueryPipelineInterface
{
    public function handle(QueryInterface $query, QueryHandlerInterface $next): object
    {
        var_dump('Before 3');
        $r = $next->handle($query);
        var_dump('After 3');

        return $r;
    }
}
