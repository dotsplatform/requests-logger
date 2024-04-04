<?php
/**
 * Description of RotateLogsOpensearchCommand.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Yehor Herasymchuk <yehor@dotsplatform.com>
 */

namespace Dotsplatform\RequestsLogger\Console\Commands\Opensearch;

use Dotsplatform\RequestsLogger\OpensearchLogger\Repositories\OpensearchProviderRequestsRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class DeleteOldLogsOpensearchCommand extends Command
{
    private const DAYS_TO_KEEP = 30;

    public $signature = 'opensearch:provider-request:delete-old-logs';

    private function getOpenSearchProviderRequestsRepository(): OpensearchProviderRequestsRepository
    {
        return app(OpensearchProviderRequestsRepository::class);
    }

    public function handle(): void
    {
        $this->getOpenSearchProviderRequestsRepository()->deleteOld(
            Carbon::now()->subDays(self::DAYS_TO_KEEP),
        );
    }
}
