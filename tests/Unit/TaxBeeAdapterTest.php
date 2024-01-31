<?php

namespace App\Tests\Unit;

use App\Service\Adapter\TaxBeeAdapter;
use App\Shared\DTO\TaxCollectionTransfer;
use App\Shared\DTO\TaxRequestTransfer;
use App\Shared\DTO\TaxTransfer;
use App\Shared\Enum\TaxTypeEnum;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaxBeeAdapterTest extends KernelTestCase
{

    /**
     * @dataProvider dataProvider
     *
     * @param string $country
     * @param string $state
     * @param array<string> $taxTypes
     * @param \App\Shared\DTO\TaxCollectionTransfer $expectedTaxCollectionTransfer
     *
     * @return void
     */
    public function testCalculateTax(string $country, string $state, array $taxTypes, TaxCollectionTransfer $expectedTaxCollectionTransfer): void
    {
        $adapter = $this->getMockBuilder(TaxBeeAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $taxRequestTransfer = (new TaxRequestTransfer())
            ->setCountry($country)
            ->setState($state)
            ->setCity('Los Angeles')
            ->setStreet('1st street')
            ->setPostcode('90001');

        $adapter->expects($this->once())
            ->method('calculateTax')
            ->with($taxRequestTransfer)
            ->willReturn($expectedTaxCollectionTransfer);

        $result = $adapter->calculateTax($taxRequestTransfer);
        $taxes = $result->getTaxes();

        $this->assertInstanceOf(TaxCollectionTransfer::class, $result);

        foreach ($taxTypes as $key => $taxType) {
            $this->assertEquals($taxType, $taxes[$key]->getTaxType());
            $this->assertEquals($expectedTaxCollectionTransfer->getTaxes()[$key]->getPercentage(), $taxes[$key]->getPercentage());
        }
    }

    /**
     * @return array<array<mixed, mixed>>
     */
    public static function dataProvider(): array
    {
        return [
            [
                'country' => 'US',
                'state' => 'california',
                'taxType' => [TaxTypeEnum::VAT->value],
                'taxCollectionTransfer' => (new TaxCollectionTransfer())->addTax(
                    (new TaxTransfer())
                        ->setTaxType(TaxTypeEnum::VAT->value)
                        ->setPercentage(7.25)
                ),
            ],
            [
                'country' => 'CA',
                'state' => 'quebec',
                'taxType' => [TaxTypeEnum::GST_HST->value, TaxTypeEnum::PST->value],
                'taxCollectionTransfer' => (new TaxCollectionTransfer())
                    ->addTax((new TaxTransfer())->setTaxType(TaxTypeEnum::GST_HST->value)->setPercentage(5.0))
                    ->addTax((new TaxTransfer())->setTaxType(TaxTypeEnum::PST->value)->setPercentage(9.975)),
            ],
            [
                'country' => 'CA',
                'state' => 'ontario',
                'taxType' => [TaxTypeEnum::GST_HST->value],
                'taxCollectionTransfer' => (new TaxCollectionTransfer())->addTax(
                    (new TaxTransfer())
                        ->setTaxType(TaxTypeEnum::GST_HST->value)
                        ->setPercentage(13.0)
                ),
            ],
        ];
    }
}
