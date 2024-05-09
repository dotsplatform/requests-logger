<?php
/**
 * Description of ProviderRequestsLoggerService.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Yehor Herasymchuk <yehor@dotsplatform.com>
 */

namespace Dotsplatform\RequestsLogger;

use Dotsplatform\RequestsLogger\DTO\ProviderRequestDTO;
use Dotsplatform\RequestsLogger\Filters\ProviderRequestSensitiveDataFilter;
use Dotsplatform\RequestsLogger\Jobs\Job;
use Dotsplatform\RequestsLogger\Jobs\LogProviderRequestsJob;
use Illuminate\Support\Facades\Log;

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
        if ($this->sizeMoreThanMaxJobSize($dto)) {
            return;
        }
        LogProviderRequestsJob::dispatch($dto);
    }

    private function sizeMoreThanMaxJobSize(ProviderRequestDTO $dto): bool
    {
        $serializedDto = serialize($dto);
        $dtoLength = strlen($serializedDto);
        if ($dtoLength / 1024 > Job::MAX_SIZE_KB) {
            Log::info('Request DTO size is more than '.Job::MAX_SIZE_KB, $dto->toArray());

            return true;
        }

        return false;
    }
}
