<?php

namespace Utils;

use DateInterval;
use DatePeriod;
use DateTime;

class DateUtils
{

    private static function isWeekDay(DateTime $timestamp): bool
    {
        $dw = (int) $timestamp->format('w');

        //0 is Sunday, 6 is Saturday
        return $dw !== 0 && $dw !== 6;
    }

    public static function countWorkdaysFromStartDateToEndDate(DateTime $startDate, DateTime $endDate): int
    {
        $startDate = clone $startDate;
        $endDate = clone $endDate;

        $inverse = 1;
        if ($startDate > $endDate) {
            $tmp = $startDate;
            $startDate = $endDate;
            $endDate = $tmp;
            $inverse = -1;
        }

        $startDate->setTime(0, 0, 0);
        $endDate->setTime(23, 59, 59);

        $interval = new DatePeriod($startDate, new DateInterval('P1D'), $endDate, DatePeriod::EXCLUDE_START_DATE);

        return count(array_filter(iterator_to_array($interval), [DateUtils::class, 'isWeekDay'])) * $inverse;
    }

}
