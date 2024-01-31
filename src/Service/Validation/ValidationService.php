<?php

namespace App\Service\Validation;

use App\Shared\DTO\TaxRequestTransfer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;


readonly class ValidationService implements ValidationServiceInterface
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $taxCalculatorStrategyClassName
     *
     * @return void
     */
    public function validateTaxRequest(Request $request, string $taxCalculatorStrategyClassName): void
    {
        $this->validateCountry($request);

        $taxRequestTransfer = (new TaxRequestTransfer())->fromArray($request->query->all());

        $violations = $this->validator->validate($taxRequestTransfer, null, [$taxCalculatorStrategyClassName]);

        if ($violations->count() < 1) {
            return;
        }

        foreach ($violations as $violation) {
            $messages[] = sprintf('%s: %s', $violation->getPropertyPath(), $violation->getMessage());
        }

        throw new BadRequestHttpException(implode(', ', $messages ?? []));
    }

    public function validateCountry(Request $request): void
    {
        if (!$request->get('country')) {
            throw new BadRequestHttpException('Country is required');
        }
    }
}
