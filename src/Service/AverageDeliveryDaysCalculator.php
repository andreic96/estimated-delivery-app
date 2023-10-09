<?php

namespace Service;

use App\ShippingRepository;
use DateTime;
use Utils\DateUtils;

class AverageDeliveryDaysCalculator
{

    private const PAGE_SIZE = 100;

    public function __construct(private readonly ShippingRepository $shippingRepository)
    {
    }

    public function calculate(string $zipCode, DateTime $historyStartDate = null, DateTime $historyEndDate = null): array
    {
        //TODO maybe we can precalculate and store in db this data
        $days = [];
        $page = 1;
        while(true) {
            $offset = ($page - 1) * self::PAGE_SIZE;
            $shippingDataByZipCode = $this->shippingRepository->findShippingByZipCodeAndInterval(
                $zipCode,
                $historyStartDate,
                $historyEndDate,
                $offset,
                self::PAGE_SIZE
            );

            if (empty($shippingDataByZipCode)) {
                break;
            }

            /** @var array<DateTime> $shippingData */
            foreach ($shippingDataByZipCode as $shippingData) {
                $days[] = DateUtils::countWorkdaysFromStartDateToEndDate($shippingData['shipmentDate'], $shippingData['deliveredDate']);
            }

            $page++;
        }

        return $days;
    }

}
