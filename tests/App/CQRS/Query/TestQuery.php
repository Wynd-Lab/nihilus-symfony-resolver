<?php

namespace Wynd\CQRSBundle\Tests\App\CQRS\Query;

use Nihilus\QueryInterface;

class TestQuery implements QueryInterface
{
    /**
     * @var string
     */
    public $test;
}