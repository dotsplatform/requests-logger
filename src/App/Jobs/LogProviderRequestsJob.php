<?php
/**
 * Description of LogProviderRequestsJob.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Yehor Herasymchuk <yehor@dotsplatform.com>
 */

namespace Dotsplatform\RequestsLogger\Jobs;

use Dotsplatform\RequestsLogger\DTO\ProviderRequestDTO;
use Dotsplatform\RequestsLogger\ProviderRequestsLogger;

class LogProviderRequestsJob extends Job
{
    public function __construct(
        private readonly ProviderRequestDTO $dto,
    ) {
    }

    private function getLogger(): ProviderRequestsLogger
    {
        return app(ProviderRequestsLogger::class);
    }

    public function handle(): void
    {
        try {
            $this->getLogger()->log($this->dto);
        } catch (\Exception $e) {
            
        }
    }
}
