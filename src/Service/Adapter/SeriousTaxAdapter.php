<?php

namespace App\Service\Adapter;

use App\ExternalService\SeriousTax\Location;
use App\ExternalService\SeriousTax\SeriousTaxService;
use App\Shared\DTO\TaxCollectionTransfer;
use App\Shared\DTO\TaxRequestTransfer;
use App\Shared\DTO\TaxTransfer;
use App\Shared\Enum\TaxTypeEnum;

class SeriousTaxAdapter implements TaxAdapterInterface
{
    public function __construct(protected SeriousTaxService $seriousTaxService)
    {
    }

    /**
     * @param \App\Shared\DTO\TaxRequestTransfer $taxRequestTransfer
     *
     * @return \App\Shared\DTO\TaxCollectionTransfer
     */
    public function calculateTax(TaxRequestTransfer $taxRequestTransfer): TaxCollectionTransfer
    {
        $location = new Location($taxRequestTransfer->getCountry(), $taxRequestTransfer->getState());

        $vatTax = $this->seriousTaxService->getTaxesResult($location);

        return (new TaxCollectionTransfer())->addTax(
            (new TaxTransfer())
                ->setTaxType(TaxTypeEnum::VAT->value)
                ->setPercentage($vatTax)
        );
    }
}
