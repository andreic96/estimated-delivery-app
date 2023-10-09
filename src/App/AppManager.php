<?php

namespace App;

use DateTime;
use Exception;
use Exception\InvalidDateTimeException;
use Exception\InvalidZipCodeException;
use Service\DeliveryDateEstimateService;
use Service\ShippingDataGeneratorService;
use Utils\DateUtils;
use Utils\ZipCodeUtils;

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
        //TODO move this to an Input class
        try {
            $zipCode = $this->readAndValidateZipCode();
            $startDate = $this->readAndValidateStartDate();
            $endDate = $this->readAndValidateEndDate();
            $calculateFromDate = new DateTime(); //TODO customise this

            $estimatedDeliveryDate = $this->deliveryDateEstimateService->estimate($zipCode, $calculateFromDate, $startDate, $endDate);
        } catch (Exception $e) {
            self::logError('ERROR on calculating estimated delivery time', $e->getMessage());
            return;
        }

        if ($estimatedDeliveryDate === null) {
            echo 'Zip code ' . $zipCode . ' not found';
        }

        echo 'Today is '.(new DateTime())->format('d-m-Y').PHP_EOL;
        echo 'Delivery expected on: '.$estimatedDeliveryDate->format('d-m-Y');
    }

    private function runShippingDataGenerator(): void
    {
        try {
            $this->shippingDataGeneratorService->generateAndSave();
        } catch (Exception $e) {
            self::logError('ERROR on generating and saving data', $e->getMessage());
        }
    }

    /**
     * @throws InvalidZipCodeException
     */
    private function readAndValidateZipCode(): string
    {
        $zipCode = trim(readline("Input the ZipCode: "));

        if (!ZipCodeUtils::isZipCodeValid($zipCode)) {
            throw new InvalidZipCodeException();
        }

        return $zipCode;
    }

    /**
     * @throws InvalidDateTimeException
     * @throws Exception
     */
    private function readAndValidateStartDate(): ?DateTime
    {
        $startDate = trim(readline("Input the Start Date for historical data (leave empty if not needed): "));
        if ($startDate === '') {
            return null;
        }

        if (!DateUtils::isValidDate($startDate)) {
            throw new InvalidDateTimeException();
        }

        return new DateTime($startDate);
    }

    /**
     * @throws InvalidDateTimeException
     * @throws Exception
     */
    private function readAndValidateEndDate(): ?DateTime
    {
        $endDate = trim(readline("Input the End Date for historical data (leave empty if not needed): "));
        if ($endDate === '') {
            return null;
        }

        if (!DateUtils::isValidDate($endDate)) {
            throw new InvalidDateTimeException();
        }

        return new DateTime($endDate);
    }

    private static function logError(string $message, string $error): void
    {
        error_log(sprintf('%s: %s', $message, $error));
    }

}
