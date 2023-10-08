<?php

namespace Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'shipping')]
class Shipping
{

    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue]
    private int $id;

    #[Column(name: 'zip_code')]
    private string $zipCode;

    #[Column(name: 'shipment_date')]
    private DateTime $shipmentDate;

    #[Column(name: 'delivered_date')]
    private DateTime $deliveredDate;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    public function getShipmentDate(): DateTime
    {
        return $this->shipmentDate;
    }

    public function setShipmentDate(DateTime $shipmentDate): void
    {
        $this->shipmentDate = $shipmentDate;
    }

    public function getDeliveredDate(): DateTime
    {
        return $this->deliveredDate;
    }

    public function setDeliveredDate(DateTime $deliveredDate): void
    {
        $this->deliveredDate = $deliveredDate;
    }

}
