<?php

namespace App\Service\CircuitBreaker;

use App\Shared\DTO\TaxCollectionTransfer;
use Exception;
use Psr\Cache\CacheItemInterface;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Contracts\Cache\CacheInterface;

class CircuitBreaker
{
    private const FAILURE_THRESHOLD = 2;
    private const STATE_OPEN = 'open';
    private const STATE_CLOSED = 'closed';
    private const STATE_HALF_OPEN = 'half-open';
    private const RESET_TIMEOUT = 30;
    private const CACHE_KEY_STATE = 'circuit_breaker_state';
    private const CACHE_KEY_FAILURE_COUNT = 'circuit_breaker_failure_count';
    private const CACHE_KEY_LAST_FAILURE_TIME = 'circuit_breaker_last_failure_time';

    public function __construct(protected CacheInterface $cache)
    {
    }

    public function callService(callable $serviceCall): TaxCollectionTransfer
    {
        $this->updateStateBasedOnTimeout();

        if ($this->getState() === self::STATE_OPEN) {
            throw new ServiceUnavailableHttpException(null, 'Service is unavailable');
        }

        try {
            $result = $serviceCall();

            $this->resetState();
            return $result;
        } catch (Exception $exception) {
            $this->recordFailure();
            throw $exception;
        }
    }

    private function updateStateBasedOnTimeout(): void
    {
        if ($this->getState() === self::STATE_OPEN && $this->hasTimeoutExpired()) {
            $this->setState(self::STATE_HALF_OPEN);
        }
    }

    private function getState(): string
    {
        return $this->cache->get(self::CACHE_KEY_STATE, function () {
            return self::STATE_CLOSED;
        });
    }

    private function setState(string $state): void
    {
        $this->cache->delete(self::CACHE_KEY_STATE);
        $this->cache->get(self::CACHE_KEY_STATE, function (CacheItemInterface $cache) use ($state) {
            $cache->expiresAfter(self::RESET_TIMEOUT);
            return $state;
        });
    }

    private function hasTimeoutExpired(): bool
    {
        $lastFailureTime = $this->cache->get(self::CACHE_KEY_LAST_FAILURE_TIME, function () {
            return 0;
        });
        return (time() - $lastFailureTime) > self::RESET_TIMEOUT;
    }

    private function recordFailure(): void
    {
        $failureCount = $this->cache->get(self::CACHE_KEY_FAILURE_COUNT, function (CacheItemInterface $cache) {
                $cache->expiresAfter(self::RESET_TIMEOUT);
                return 0;
            }) + 1;

        $this->cache->delete(self::CACHE_KEY_FAILURE_COUNT);
        $this->cache->get(self::CACHE_KEY_FAILURE_COUNT, function (CacheItemInterface $cache) use ($failureCount) {
            $cache->expiresAfter(self::RESET_TIMEOUT);
            return $failureCount;
        });

        $this->cache->delete(self::CACHE_KEY_LAST_FAILURE_TIME);
        $this->cache->get(self::CACHE_KEY_LAST_FAILURE_TIME, function (CacheItemInterface $cache) {
            $cache->expiresAfter(self::RESET_TIMEOUT);
            return time();
        });

        if ($failureCount > self::FAILURE_THRESHOLD) {
            $this->setState(self::STATE_OPEN);
        }
    }

    private function resetState(): void
    {
        $this->setState(self::STATE_CLOSED);
        $this->cache->delete(self::CACHE_KEY_FAILURE_COUNT);
        $this->cache->get(self::CACHE_KEY_FAILURE_COUNT, function () {
            return 0;
        });
    }
}
