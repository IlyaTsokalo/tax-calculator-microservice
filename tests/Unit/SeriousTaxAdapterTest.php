<?php

namespace App\Tests\Unit;

use App\Service\Adapter\SeriousTaxAdapter;
use App\Shared\DTO\TaxRequestTransfer;
use App\Shared\DTO\TaxCollectionTransfer;
use App\Shared\DTO\TaxTransfer;
use App\Shared\Enum\TaxTypeEnum;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SeriousTaxAdapterTest extends KernelTestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testCalculateTax(string $country, float $expectedVat): void
    {
        $adapter = $this->getMockBuilder(SeriousTaxAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $taxRequestTransfer = (new TaxRequestTransfer())
            ->setCountry($country)
            ->setState('test');

        $adapter->expects($this->once())
            ->method('calculateTax')
            ->with($taxRequestTransfer)
            ->willReturn(
                (new TaxCollectionTransfer())->addTax(
                    (new TaxTransfer())
                        ->setTaxType(TaxTypeEnum::VAT->value)
                        ->setPercentage($expectedVat)
                )
            );

        $result = $adapter->calculateTax($taxRequestTransfer);
        $taxes = $result->getTaxes();


        $this->assertInstanceOf(TaxCollectionTransfer::class, $result);
        $this->assertEquals(TaxTypeEnum::VAT->value, $taxes[0]->getTaxType());
        $this->assertEquals($expectedVat, $taxes[0]->getPercentage());
    }

    /**
     * @return array<array<mixed, mixed>>
     */
    public static function dataProvider(): array
    {
        return [
            [
                'country' => 'LT',
                'expected' => 21.0,
            ],
            [
                'country' => 'LV',
                'expected' => 22.0,
            ],
            [
                'country' => 'EE',
                'expected' => 20.0,
            ]
        ];
    }
}
