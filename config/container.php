<?php

use Connection\ConnectionInterface;
use Connection\ConnectionMysql;
use DI\Container;

$container = new Container();
$container->set(ConnectionInterface::class, DI\create(ConnectionMysql::class));

return $container;
