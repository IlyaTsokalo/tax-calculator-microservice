<?php

namespace App\Controller;

use App\Service\Strategy\Selector\TaxCalculatorStrategySelectorInterface;
use App\Service\TaxCalculation\TaxCalculationServiceInterface;
use App\Service\Validation\ValidationServiceInterface;
use App\Shared\DTO\TaxRequestTransfer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Throwable;

readonly class TaxesController
{
    public function __construct(
        protected TaxCalculationServiceInterface         $taxCalculationService,
        protected ValidationServiceInterface             $validationService,
        protected RateLimiterFactory                     $anonymousApiLimiter,
        protected TaxCalculatorStrategySelectorInterface $taxCalculatorStrategySelector,
    )
    {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[Route('/taxes', name: 'get_taxes', methods: ['GET'])]
    public function getTaxes(Request $request): Response
    {
        try {
            $this->checkRateLimit($request);

            $this->validationService->validateCountry($request);

            $taxCalculatorStrategy = $this->taxCalculatorStrategySelector->selectStrategy($request->get('country'));

            $this->validationService->validateTaxRequest($request, $taxCalculatorStrategy::class);

            $taxRequestTransfer = (new TaxRequestTransfer())->fromArray($request->query->all());

            $taxCollectionTransfer = $this->taxCalculationService->calculateTax($taxRequestTransfer, $taxCalculatorStrategy);

            return new JsonResponse($taxCollectionTransfer, JsonResponse::HTTP_OK);
        } catch (Throwable $exception) {
            return $this->handleException($exception);
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    private function checkRateLimit(Request $request): void
    {
        $limiter = $this->anonymousApiLimiter->create($request->getClientIp());
        if ($limiter->consume()->isAccepted() === false) {
            throw new TooManyRequestsHttpException(null, 'Too many requests');
        }
    }

    /**
     * @param \Throwable $exception
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    private function handleException(Throwable $exception): JsonResponse
    {
        $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : Response::HTTP_BAD_REQUEST;
        $message = $exception->getMessage();

        return new JsonResponse(['error' => $message], $statusCode);
    }
}
