<?php
/**
 * Description of OpenSearch.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Yehor Herasymchuk <yehor@dotsplatform.com>
 */

namespace Dotsplatform\RequestsLogger\Console\Commands\Opensearch;

use Dotsplatform\RequestsLogger\OpensearchLogger\Repositories\OpensearchProviderRequestsRepository;
use Illuminate\Console\Command;

class SearchLoggedItemsOpensearchCommand extends Command
{
    public $signature = 'opensearch:provider-request:search';

    private function getOpenSearchProviderRequestsRepository(): OpensearchProviderRequestsRepository
    {
        return app(OpensearchProviderRequestsRepository::class);
    }

    public function handle(): void
    {
        $items = $this->getOpenSearchProviderRequestsRepository()->search(1, 0);
        dd($items);
    }
}
