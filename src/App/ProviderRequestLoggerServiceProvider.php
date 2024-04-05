<?php
/**
 * Description of ProviderRequestLoggerServiceProvider.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Yehor Herasymchuk <yehor@dotsplatform.com>
 */

namespace Dotsplatform\RequestsLogger;

use Dotsplatform\RequestsLogger\Console\Commands\Opensearch\CreateProviderRequestIndexOpensearchCommand;
use Dotsplatform\RequestsLogger\Console\Commands\Opensearch\DeleteOldLogsOpensearchCommand;
use Dotsplatform\RequestsLogger\Console\Commands\Opensearch\SearchLoggedItemsOpensearchCommand;
use Dotsplatform\RequestsLogger\DTO\RequestLoggerChannel;
use Dotsplatform\RequestsLogger\LaravelLogger\LaravelProviderRequestsLogger;
use Dotsplatform\RequestsLogger\OpensearchLogger\OpensearchProviderRequestsLogger;
use Illuminate\Log\LogManager;
use Illuminate\Support\ServiceProvider;
use OpenSearch\Client;
use OpenSearch\ClientBuilder;

class ProviderRequestLoggerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->bindLoggerClient();
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/requests-logger.php' => config_path('requests-logger.php'),
        ], 'config');

        $this->commands([
            CreateProviderRequestIndexOpensearchCommand::class,
            DeleteOldLogsOpensearchCommand::class,
            SearchLoggedItemsOpensearchCommand::class,
        ]);
    }

    private function bindLoggerClient(): void
    {
        $this->app->singleton(ProviderRequestsLogger::class, function () {
            $channel = config('requests-logger.default');
            if ($channel === RequestLoggerChannel::SYSTEM) {
                return app(LaravelProviderRequestsLogger::class, [
                    'logger' => new LogManager($this->app),
                ]);
            }
            if ($channel === RequestLoggerChannel::OPENSEARCH) {
                return app(OpensearchProviderRequestsLogger::class);
            }

            return new NullProviderRequestsLogger();
        });

        $this->app->bind(Client::class, function () {
            return ClientBuilder::create()
                ->setHosts(config('requests-logger.channels.opensearch.hosts'))
                ->setBasicAuthentication(
                    config('requests-logger.channels.opensearch.username'),
                    config('requests-logger.channels.opensearch.password'),
                )
                ->build();
        });
    }
}
