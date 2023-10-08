<?php

namespace Command;

use App\ShippingRepository;
use DateTime;
use Utils\DateUtils;

class ShippingEstimateCommand
{

    private const PAGE_SIZE = 100;

    public function __construct(private readonly ShippingRepository $shippingRepository)
    {
    }

    public function estimate(string $zipCode): ?int
    {
        $days = [];
        $page = 1;
        while(true) {
            $offset = ($page - 1) * self::PAGE_SIZE;
            $shippingDataByZipCode = $this->shippingRepository->findShippingByZipCode($zipCode, $offset, self::PAGE_SIZE);

            if (empty($shippingDataByZipCode)) {
                break;
            }

            $this->calculateDays($shippingDataByZipCode, $days);
            $page++;
        }

        return !empty($days) ? round(array_sum($days) / count($days)) : null;
    }

    private function calculateDays(array $shippingDataByZipCode, array &$days): void
    {
        /** @var array<DateTime> $shippingData */
        foreach ($shippingDataByZipCode as $shippingData) {
            $days[] = DateUtils::countWorkdaysFromStartDateToEndDate($shippingData['shipmentDate'], $shippingData['deliveredDate']);
        }
    }

}
