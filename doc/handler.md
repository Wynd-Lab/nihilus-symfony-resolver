### Handler

A handler, is responsible for the execution of a query / command. it can in the case of a query return the requested information with the criteria present in the body of the query and in the case of a command simply do what he asked him

#### How to register handler

```yaml
services:
  _defaults:
    autowire: true
    autoconfigure: true
    public: false

  Nihilus\CQRSBundle\Tests\App\CQRS\Command\Handler\:
    resource: '../CQRS/Command/Handler/*'
    
  Nihilus\CQRSBundle\Tests\App\CQRS\Query\Handler\:
    resource: '../CQRS/Query/Handler/*'
```

```php
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
        return /** anything */;
    }

    public static function getHandledClass(): string
    {
        return TestQuery::class;
    }
}
```