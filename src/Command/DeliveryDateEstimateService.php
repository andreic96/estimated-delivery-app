<?php

namespace Command;

use App\ShippingRepository;
use DateTime;
use Utils\DateUtils;

class DeliveryDateEstimateService
{

    private const PAGE_SIZE = 100;

    public function __construct(private readonly ShippingRepository $shippingRepository)
    {
    }

    public function estimate(string $zipCode, DateTime $startDate = null, DateTime $endDate = null): ?DateTime
    {
        //TODO maybe we can precalculate and store in db this data
        $days = [];
        $page = 1;
        while(true) {
            $offset = ($page - 1) * self::PAGE_SIZE;
            $shippingDataByZipCode = $this->shippingRepository->findShippingByZipCodeAndInterval(
                $zipCode,
                $startDate,
                $endDate,
                $offset,
                self::PAGE_SIZE
            );

            if (empty($shippingDataByZipCode)) {
                break;
            }

            $this->calculateDays($shippingDataByZipCode, $days);
            $page++;
        }

        if (!empty($days)) {
            $averageDays = round(array_sum($days) / count($days));
            echo $averageDays."\n";
            return (new DateTime())->modify('+'.$averageDays.' Weekdays');
        }

        return null;
    }

    private function calculateDays(array $shippingDataByZipCode, array &$days): void
    {
        /** @var array<DateTime> $shippingData */
        foreach ($shippingDataByZipCode as $shippingData) {
            $days[] = DateUtils::countWorkdaysFromStartDateToEndDate($shippingData['shipmentDate'], $shippingData['deliveredDate']);
        }
    }

}
