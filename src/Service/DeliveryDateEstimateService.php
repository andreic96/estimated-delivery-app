<?php

namespace Service;

use DateTime;

class DeliveryDateEstimateService
{

    public function __construct(private readonly AverageDeliveryDaysCalculator $averageDeliveryDaysCalculator)
    {
    }

    public function estimate(string $zipCode, DateTime $calculateFromDate, DateTime $historyStartDate = null, DateTime $historyEndDate = null): ?DateTime
    {
        $days = $this->averageDeliveryDaysCalculator->calculate($zipCode, $historyStartDate, $historyEndDate);

        if (empty($days)) {
            return null;
        }

        $averageDays = round(array_sum($days) / count($days));
        return $calculateFromDate->modify('+'.$averageDays.' Weekdays');
    }

}
