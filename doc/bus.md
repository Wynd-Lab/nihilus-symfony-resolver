### Bus

A bus is responsible for transmitting a message to the handler who is responsible for it. it is neither more nor less than an intermediary that makes it possible to free oneself from a direct dependence. The mediator pattern is used for this.

#### How to send query

````php
use Nihilus\QueryBusInterface;

$bus = $contaner->get(QueryBusInterface::class);
$bus->execute(/** $query */);
````

#### How to send command

````php
use Nihilus\CommandBusInterface;

$bus = $contaner->get(CommandBusInterface::class);
$bus->execute(/** $command */);
````