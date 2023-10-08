<?php

use Command\ShippingDataGeneratorCommand;
use PHPUnit\Framework\TestCase;

class ShippingDataGeneratorTest extends TestCase
{
    public function testGenerateStartDateLowerThanEndDate(): void
    {
        $shippingDataGenerator = new ShippingDataGeneratorCommand();
        $dates = $shippingDataGenerator->generate();

        $this->assertLessThan($dates['endDate'], $dates['startDate']);
    }

}
