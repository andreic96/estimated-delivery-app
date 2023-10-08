<?php

use Command\ShippingDataGeneratorCommand;
use Command\DeliveryDateEstimateService;

require __DIR__ . '/../vendor/autoload.php';
$container = require __DIR__ . '/../config/container.php';

$option = (int)readline("1) Estimate shipping \n 2) Generate shipping data ");

//TODO validate date inputs (check format and if startDate < endDate)
switch ($option){
    case 1:
        $zipCode = '68298';
        $startDate = new DateTime('2023-03-25 18:40:52');
        $endDate = new DateTime('2023-04-18 10:06:00');

        /** @var DeliveryDateEstimateService $deliveryDateEstimateService */
        $deliveryDateEstimateService = $container->get(DeliveryDateEstimateService::class);
        $estimatedDeliveryDate = $deliveryDateEstimateService->estimate($zipCode, $startDate, $endDate);

        if ($estimatedDeliveryDate === null) {
            echo 'Zip code ' . $zipCode . ' not found';
        }

        echo $estimatedDeliveryDate->format('d-m-Y');

        break;
    case 2:
        /** @var ShippingDataGeneratorCommand $shippingDataGenerator */
        $shippingDataGenerator = $container->get(ShippingDataGeneratorCommand::class);
        $shippingDataGenerator->generateAndSave();
        break;
    default:
        echo 'No such option';
}
