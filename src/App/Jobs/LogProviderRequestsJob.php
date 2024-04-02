<?php
/**
 * Description of LogProviderRequestsJob.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Yehor Herasymchuk <yehor@dotsplatform.com>
 */

namespace Dotsplatform\RequestsLogger\Jobs;

use Dotsplatform\RequestsLogger\DTO\ProviderRequestDTO;
use Dotsplatform\RequestsLogger\ProviderRequestsLogger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class LogProviderRequestsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

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
        $this->getLogger()->log($this->dto);
    }
}
