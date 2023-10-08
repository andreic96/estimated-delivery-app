<?php

use Command\ShippingDataGeneratorCommand;

require __DIR__ . '/../vendor/autoload.php';
$container = require __DIR__ . '/../config/container.php';

//TODO add some menu? or separate commands
/** @var ShippingDataGeneratorCommand $shippingDataGenerator */
$shippingDataGenerator = $container->get(ShippingDataGeneratorCommand::class);
$shippingDataGenerator->generate();

