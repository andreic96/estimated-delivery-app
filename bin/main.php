<?php

use Command\ShippingDataGeneratorCommand;
use Command\ShippingEstimateCommand;

require __DIR__ . '/../vendor/autoload.php';
$container = require __DIR__ . '/../config/container.php';

$option = (int)readline("1) Estimate shipping \n 2) Generate shipping data ");

switch ($option){
    case 1:
        $zipCode = '26400';
        /** @var ShippingEstimateCommand $shippingEstimateCommand */
        $shippingEstimateCommand = $container->get(ShippingEstimateCommand::class);
        $estimatedDays = $shippingEstimateCommand->estimate($zipCode);
        echo $estimatedDays;
        if ($estimatedDays === null) {
            echo 'Zip code ' . $zipCode . ' not found';
        }

        break;
    case 2:
        /** @var ShippingDataGeneratorCommand $shippingDataGenerator */
        $shippingDataGenerator = $container->get(ShippingDataGeneratorCommand::class);
        $shippingDataGenerator->generateAndSave();
        break;
    default:
        echo 'No such option';
}
