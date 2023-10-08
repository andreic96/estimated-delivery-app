<?php

namespace Command;

use Connection\ConnectionInterface;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\Mapping\MappingException;
use Entity\Shipping;
use Faker\Factory;
use Faker\Generator;

class ShippingDataGeneratorCommand
{

    private const MIN_DELIVERY_DAYS = 3;
    private const MAX_DELIVERY_DAYS = 14;
    private const DEFAULT_START_DATE = '-1 year';
    private const DATE_TIME_FORMAT = 'd-m-Y H:i:s';
    private const BATCH_SIZE = 100;

    private Generator $faker;
    private EntityManager $em;

    public function __construct(
        private readonly ConnectionInterface $dbConnection,
        private readonly int $nbZipCodes = 10,
        private readonly int $nbDatesPerZipCode = 1000
    ) {
        $this->faker = Factory::create();
        $this->em = $this->dbConnection->getConnection();
        self::init();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws MappingException
     */
    public function generate(): void
    {
        $zipCodes = $this->generateZipCodes($this->nbZipCodes);
        foreach ($zipCodes as $zipCode) {
            $allData = [];
            for ($i = 0; $i < $this->nbDatesPerZipCode; $i++) {
                $allData[] = $this->generateDates();
            }

            $this->saveData($zipCode, $allData);
        }
    }

    private function init(): void
    {

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

    /**
     * @param array<array<DateTime>> $allData
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws MappingException
     */
    private function saveData(string $zipCode, array $allData): void
    {
        foreach ($allData as $key => $data) {
            $shipping = new Shipping();
            $shipping->setZipCode($zipCode);
            $shipping->setShipmentDate($data['startDate']);
            $shipping->setDeliveredDate($data['endDate']);
            $this->em->persist($shipping);

            if (($key % self::BATCH_SIZE) === 0) {
                $this->em->flush();
                $this->em->clear();
            }
        }

        $this->em->flush();
        $this->em->clear();
    }

}
