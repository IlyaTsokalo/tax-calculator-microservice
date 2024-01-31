<?php

declare(strict_types=1);

namespace App\Service\Mock;

use App\Service\Adapter\TaxAdapterInterface;
use App\Shared\DTO\TaxCollectionTransfer;
use App\Shared\DTO\TaxRequestTransfer;
use App\Shared\DTO\TaxTransfer;
use Exception;

class MockTaxAdapter implements TaxAdapterInterface
{
    public function calculateTax(TaxRequestTransfer $taxRequestTransfer): TaxCollectionTransfer
    {
        $taxCollectionTransfer = new TaxCollectionTransfer();

        if ($taxRequestTransfer->getCountry() === 'PL') {
            throw new Exception('Some custom exception');
        }

        if ($taxRequestTransfer->getCountry() === 'LT') {
            $taxCollectionTransfer->addTax(
                (new TaxTransfer())
                    ->setTaxType('VAT')
                    ->setPercentage(21.0)
            );
        }

        if ($taxRequestTransfer->getCountry() === 'LV') {
            $taxCollectionTransfer->addTax(
                (new TaxTransfer())
                    ->setTaxType('VAT')
                    ->setPercentage(22.0)
            );
        }

        if ($taxRequestTransfer->getCountry() === 'EE') {
            $taxCollectionTransfer->addTax(
                (new TaxTransfer())
                    ->setTaxType('VAT')
                    ->setPercentage(20.0)
            );
        }

        if ($taxRequestTransfer->getCountry() === 'US') {
            $taxCollectionTransfer->addTax(
                (new TaxTransfer())
                    ->setTaxType('VAT')
                    ->setPercentage(20.0)
            );
        }

        if ($taxRequestTransfer->getCountry() === 'CA' && $taxRequestTransfer->getState() === 'quebec') {
            $taxCollectionTransfer->addTax(
                (new TaxTransfer())
                    ->setTaxType('GST_HST')
                    ->setPercentage(5.0)
            );
            $taxCollectionTransfer->addTax(
                (new TaxTransfer())
                    ->setTaxType('PST')
                    ->setPercentage(9.975)
            );
        }

        if ($taxRequestTransfer->getCountry() === 'CA' && $taxRequestTransfer->getState() === 'ontario') {
            $taxCollectionTransfer->addTax(
                (new TaxTransfer())
                    ->setTaxType('GST_HST')
                    ->setPercentage(13.0)
            );
        }

        return $taxCollectionTransfer;
    }
}
