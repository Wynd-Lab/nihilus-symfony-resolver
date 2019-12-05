<?php

declare(strict_types=1);

namespace Nihilus\CQRSBundle\Tests\App\CQRS\Query;

use Nihilus\QueryInterface;

class TestQuery implements QueryInterface
{
    /**
     * @var string
     */
    public $test;
}