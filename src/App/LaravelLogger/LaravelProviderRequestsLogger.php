<?php
/**
 * Description of LaravelLogger.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Yehor Herasymchuk <yehor@dotsplatform.com>
 */

namespace Dotsplatform\RequestsLogger\LaravelLogger;

use Dotsplatform\RequestsLogger\DTO\ProviderRequestDTO;
use Dotsplatform\RequestsLogger\ProviderRequestsLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LaravelProviderRequestsLogger implements ProviderRequestsLogger
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function log(ProviderRequestDTO $dto): void
    {
        $this->logger->pushHandler(
            new StreamHandler(
                config('requests-logger.channels.file.path'),
                Level::Info,
            )
        );

        $this->logger->log(
            Level::Info,
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
