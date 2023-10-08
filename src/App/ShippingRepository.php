<?php

namespace App;

use Connection\ConnectionInterface;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\Mapping\MappingException;
use Entity\Shipping;

class ShippingRepository
{
    private const BATCH_SIZE = 100;

    private EntityManager $em;

    public function __construct(private readonly ConnectionInterface $dbConnection)
    {
        $this->em = $this->dbConnection->getConnection();
    }

    /**
     * @param array<DateTime> $datesList
     * @throws ORMException
     * @throws MappingException
     */
    public function saveAllData(string $zipCode, array $datesList): void
    {
        /**
         * @var int $key
         * @var DateTime $data
         */
        foreach ($datesList as $key => $data) {
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
