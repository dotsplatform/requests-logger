<?php
/**
 * Description of DeleteOldLogsCommand.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Mamontov Bogdan <bohdan.mamontov@dotsplatform.com>
 */

namespace Dotsplatform\RequestsLogger\Console\Commands;

use Dotsplatform\RequestsLogger\OpensearchLogger\Repositories\OpensearchProviderRequestsRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class DeleteOldLogsCommand extends Command
{
    protected $signature = 'logs:old:delete';

    protected $description = 'Delete logs recorded more than 30 days ago';

    private function getOpensearchProviderRequestsRepository(): OpensearchProviderRequestsRepository
    {
        return app(OpensearchProviderRequestsRepository::class);
    }

    public function handle(): void
    {
        $this->getOpensearchProviderRequestsRepository()->deleteOld($this->getDate());
    }

    private function getDate(): Carbon
    {
        $currentDate = Carbon::now();
        return $currentDate->subDays(30);
    }
}