<?php

namespace App;

use DateTime;
use Exception;
use Service\DeliveryDateEstimateService;
use Service\ShippingDataGeneratorService;

class AppManager
{

    public function __construct(
        private readonly DeliveryDateEstimateService $deliveryDateEstimateService,
        private readonly ShippingDataGeneratorService $shippingDataGeneratorService,
    ) {
    }

    public function run(): void
    {
        $option = (int)readline("1) Estimate shipping \n 2) Generate shipping data ");

        switch ($option){
            case 1:
                $this->runDeliveryEstimate();
                break;
            case 2:
                $this->runShippingDataGenerator();
                break;
            default:
                echo 'No such option';
        }
    }

    private function runDeliveryEstimate(): void
    {
        //TODO validate date inputs (check format and if startDate < endDate)
        $zipCode = '68298';
        $startDate = new DateTime('2023-03-25 18:40:52');
        $endDate = new DateTime('2023-04-18 10:06:00');

        $estimatedDeliveryDate = null;
        try {
            $estimatedDeliveryDate = $this->deliveryDateEstimateService->estimate($zipCode, new DateTime(), $startDate, $endDate);
        } catch (Exception $e) {
            self::logError($e->getMessage());
            return;
        }

        if ($estimatedDeliveryDate === null) {
            echo 'Zip code ' . $zipCode . ' not found';
        }

        echo $estimatedDeliveryDate->format('d-m-Y');
    }

    private function runShippingDataGenerator(): void
    {
        try {
            $this->shippingDataGeneratorService->generateAndSave();
        } catch (Exception $e) {
            self::logError($e->getMessage());
        }
    }

    private static function logError(string $error): void
    {
        error_log(sprintf('ERROR on generating and saving data: %s', $error));
    }

}
