<?php

use App\ShippingRepository;
use PHPUnit\Framework\TestCase;
use Service\AverageDeliveryDaysCalculator;
use Service\DeliveryDateEstimateService;

class DeliveryEstimateServiceTest extends TestCase
{

    /**
     * @dataProvider shippingDatesProvider
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws Exception
     */
    public function testValidDataDeliveryEstimate(
        string $calculateFromDate,
        ?string $expectedDate,
        array $arrayOfDays
    ): void
    {
        $averageDeliveryDaysCalculator = $this->createMock(AverageDeliveryDaysCalculator::class);
        $deliveryEstimateService = new DeliveryDateEstimateService($averageDeliveryDaysCalculator);

        $averageDeliveryDaysCalculator->expects($this->once())
            ->method('calculate')
            ->with('12345')
            ->willReturn($arrayOfDays);

        $estimatedDeliveryTime = $deliveryEstimateService->estimate('12345', new DateTime($calculateFromDate));

        if ($expectedDate !== null) {
            $this->assertEquals($expectedDate, $estimatedDeliveryTime->format('Y-m-d'));
        } else {
            $this->assertNull($expectedDate);
        }
    }

    public static function shippingDatesProvider(): array
    {
        // from_date, expected_date
        return [
            ['2023-10-05', '2023-10-13', [4,5,7,5,5,6,10,12,7,7,3]], // approx. 6 working days
            ['2023-10-05', '2023-10-18', [12,13,9,10,4,4,9,10]], // approx. 9 working days
            ['2023-08-07', null, []],
        ];
    }

}
