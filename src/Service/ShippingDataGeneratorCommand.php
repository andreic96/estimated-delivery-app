<?php

namespace Service;

use App\ShippingRepository;
use DateTime;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\Mapping\MappingException;
use Faker\Factory;
use Faker\Generator;

class ShippingDataGeneratorCommand
{

    private const MIN_DELIVERY_DAYS = 3;
    private const MAX_DELIVERY_DAYS = 14;
    private const DEFAULT_START_DATE = '-1 year';
    private const DATE_TIME_FORMAT = 'd-m-Y H:i:s';

    private Generator $faker;

    public function __construct(
        private readonly ShippingRepository $shippingRepository,
        private readonly int $nbZipCodes = 10,
        private readonly int $nbDatesPerZipCode = 1000
    ) {
        $this->faker = Factory::create();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws MappingException
     */
    public function generateAndSave(): void
    {
        $zipCodes = $this->generateZipCodes($this->nbZipCodes);
        foreach ($zipCodes as $zipCode) {
            $datesList = $this->generateDatesList();
            $this->shippingRepository->saveAllData($zipCode, $datesList);
        }
    }

    /** @return array<DateTime> */
    private function generateDates(): array
    {
        $randomDays = $this->faker->numberBetween(self::MIN_DELIVERY_DAYS, self::MAX_DELIVERY_DAYS);
        $startDate = $this->faker->dateTimeBetween(self::DEFAULT_START_DATE)->format(self::DATE_TIME_FORMAT);

        $endDate = date(
            self::DATE_TIME_FORMAT,
            strtotime($startDate." +$randomDays Weekday")
        );

        $startDate = date_create_from_format(self::DATE_TIME_FORMAT, $startDate);
        $endDate = date_create_from_format(self::DATE_TIME_FORMAT, $endDate);

        $endDate->setTime(
            $this->faker->numberBetween(0, 23),
            $this->faker->numberBetween(0, 59),
            $this->faker->numberBetween(0, 59)
        );

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }

    /** @return array<string> */
    private function generateZipCodes(int $nbZipCodes): array
    {
        $zipCodes = [];
        for ($i = 0; $i < $nbZipCodes; $i++) {
            $zipCodes[] = $this->faker->postcode();
        }

        return $zipCodes;
    }

    private function generateDatesList(): array
    {
        $allData = [];
        for ($i = 0; $i < $this->nbDatesPerZipCode; $i++) {
            $allData[] = $this->generateDates();
        }

        return $allData;
    }

}
