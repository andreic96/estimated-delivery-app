<?php

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\Mapping\MappingException;
use PHPUnit\Framework\TestCase;
use Repository\ShippingRepository;
use Service\ShippingDataGeneratorService;

class ShippingDataGeneratorTest extends TestCase
{
    /**
     * @throws OptimisticLockException
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws MappingException
     * @throws ORMException
     */
    public function testGenerateStartDateLowerThanEndDate(): void
    {
        $shippingRepository = $this->createMock(ShippingRepository::class);
        $shippingDataGenerator = new ShippingDataGeneratorService($shippingRepository, 5, 100);

        $shippingRepository->expects($this->exactly(5))
            ->method('saveAllData');

        $shippingDataGenerator->generateAndSave();
    }

}
