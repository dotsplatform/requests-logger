<?php
/**
 * Description of OpenSearch.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Yehor Herasymchuk <yehor@dotsplatform.com>
 */

namespace Dotsplatform\RequestsLogger\Console\Commands\Opensearch;

use Dotsplatform\RequestsLogger\OpensearchLogger\Repositories\OpensearchProviderRequestsRepository;
use Illuminate\Console\Command;

class CreateProviderRequestIndexOpensearchCommand extends Command
{
    public $signature = 'opensearch:provider-request:index';

    private function getOpenSearchProviderRequestsRepository(): OpensearchProviderRequestsRepository
    {
        return app(OpensearchProviderRequestsRepository::class);
    }

    public function handle(): void
    {
        try {
            $this->info('Deleting index');
            $this->getOpenSearchProviderRequestsRepository()->deleteIndex();
            $this->info('Index deleted');
        } catch (\Throwable) {
            $this->info('No index to delete');
        }

        $this->info('Creating index');
        $this->getOpenSearchProviderRequestsRepository()->createIndex();
        $this->info('Index created');
    }
}
