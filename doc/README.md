## Getting started

Bundle provide a integration of component nihilus/cqrs with symfony
- dependency injeciton tag 'nihilus.cqrs.command_middleware' for register command middleware
- dependency injection tag 'nihilus.cqrs.query_middleware' for register query middleware
- dependency injection tag 'nihilus.cqrs.command_handler' for register command handler
- dependency injection tag 'nihilus.cqrs.query_handler' for register query handler
- command bus register in container with name 'Nihilus\CommandBusInterface'
- query bus register in container with name 'Nihilus\QueryBusInterface'
- provide container middleware resolver
- provide container handler resolver
- debug handler with command symfony 'bin/console debug:cqrs:handler'

Installation
============

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require wynd/nihilus-symfony-resolver
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require wynd/nihilus-symfony-resolver
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Nihilus\CQRSBundle\NihilusCQRSBUndle::class => ['all' => true],
];
```

#### Configuration

```yaml
nihilus_cqrs:
  query:
    middlewares:
      global: ['test1']
      transaction: ['@global', 'test2']
    binding:
      Nihilus\CQRSBundle\Tests\App\CQRS\Query\Handler\TestQuery: ['@transaction', 'test3']
  command:
    middlewares: ~
    binding: ~
```

#### Cookbook
- [Handler](handler.md)
- [Middleware](middleware.md)
- [Bus](bus.md)
- [Debug](debug.md)