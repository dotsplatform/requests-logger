<?php
/**
 * Description of LaravelLogger.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Yehor Herasymchuk <yehor@dotsplatform.com>
 */

namespace Dotsplatform\RequestsLogger\LaravelLogger;

use Dotsplatform\RequestsLogger\DTO\ProviderRequestDTO;
use Dotsplatform\RequestsLogger\ProviderRequestsLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class LaravelProviderRequestsLogger implements ProviderRequestsLogger
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function log(ProviderRequestDTO $dto): void
    {
        $this->logger->log(
            LogLevel::INFO,
            $this->generateMessage($dto),
            $dto->toArray(),
        );
    }

    private function generateMessage(ProviderRequestDTO $dto): string
    {
        return sprintf(
            'Provider request: %s %s',
            $dto->getMethod(),
            $dto->getUrl(),
        );
    }
}
