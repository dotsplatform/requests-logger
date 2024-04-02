<?php
/**
 * Description of ProviderRequestsLoggerService.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Yehor Herasymchuk <yehor@dotsplatform.com>
 */

namespace Dotsplatform\RequestsLogger;

use Dotsplatform\RequestsLogger\DTO\ProviderRequestDTO;
use Dotsplatform\RequestsLogger\Filters\ProviderRequestSensitiveDataFilter;
use Dotsplatform\RequestsLogger\Jobs\LogProviderRequestsJob;

class ProviderRequestsLoggerService
{
    public function __construct(
        private readonly ProviderRequestSensitiveDataFilter $providerRequestSensitiveDataFilter,
    ) {
    }

    public function log(ProviderRequestDTO $dto): void
    {
        if (! config('requests-logger.enabled')) {
            return;
        }
        $dto = $this->providerRequestSensitiveDataFilter->filter($dto);
        LogProviderRequestsJob::dispatch($dto);
    }
}
