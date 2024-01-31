<?php

namespace App\Tests\Acceptance;

use App\Shared\Enum\TaxTypeEnum;
use App\Tests\Support\AcceptanceTester;
use Codeception\Example;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group TaxCalculationCest
 */
class TaxCalculationCest
{
    /**
     * @dataProvider urlDataProvider
     *
     * @param \App\Tests\Support\AcceptanceTester $I
     * @param \Codeception\Example<array{url: string, code: int, expectedTaxes: array}> $providedData
     *
     * @return void
     */
    public function testCalculateTax(AcceptanceTester $I, Example $providedData): void
    {
        $I->amOnPage($providedData['url']);

        $I->seeResponseCodeIs($providedData['code']);
        $I->seeResponseIsJson();

        $I->seeResponseContainsJson($providedData['expectedTaxes']);
    }

    /**
     * @return array<array<mixed, mixed>>
     */
    protected function urlDataProvider(): array
    {
        return [
            // Successful cases
            'Success US' => [
                'url' => '/taxes?country=US&state=CA&city=Los%20Angeles&street=1st%20street&postcode=90001',
                'code' => Response::HTTP_OK,
                'expectedTaxes' => [[
                    'taxType' => TaxTypeEnum::VAT->value,
                    'percentage' => 20,
                ]]
            ],
            'Success EU' => [
                'url' => '/taxes?country=LV&state=test',
                'code' => Response::HTTP_OK,
                'expectedTaxes' => [[
                    'taxType' => TaxTypeEnum::VAT->value,
                    'percentage' => 22,
                ]]
            ],

            // Failure cases
            'Missing Country' => [
                'url' => '/taxes?state=CA',
                'code' => Response::HTTP_BAD_REQUEST,
                'expectedTaxes' => [
                    'error' => 'Country is required',
                ],
            ],
            'Unsupported Country' => [
                'url' => '/taxes?country=FR',
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'expectedTaxes' => [
                    'error' => 'No applicable strategy found for country: FR',
                ],
            ],
            'Missing State' => [
                'url' => '/taxes?country=US&city=Los%20Angeles&street=1st%20street&postcode=90001',
                'code' => Response::HTTP_BAD_REQUEST,
                'expectedTaxes' => [
                    'error' => 'state: This value should not be blank.',
                ],
            ],
            'Missing City' => [
                'url' => '/taxes?country=US&state=CA&street=1st%20street&postcode=90001',
                'code' => Response::HTTP_BAD_REQUEST,
                'expectedTaxes' => [
                    'error' => 'city: This value should not be blank.',
                ],
            ],
            'Missing Street' => [
                'url' => '/taxes?country=US&state=CA&city=Los%20Angeles&postcode=90001',
                'code' => Response::HTTP_BAD_REQUEST,
                'expectedTaxes' => [
                    'error' => 'street: This value should not be blank.',
                ],
            ],
            'Missing Postcode' => [
                'url' => '/taxes?country=US&state=CA&city=Los%20Angeles&street=1st%20street',
                'code' => Response::HTTP_BAD_REQUEST,
                'expectedTaxes' => [
                    'error' => 'postcode: This value should not be blank.',
                ],
            ]
        ];
    }
}
