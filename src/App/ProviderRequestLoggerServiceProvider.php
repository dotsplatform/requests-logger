<?php
/**
 * Description of ProviderRequestLoggerServiceProvider.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Yehor Herasymchuk <yehor@dotsplatform.com>
 */

namespace Dotsplatform\RequestsLogger;

use Dotsplatform\RequestsLogger\DTO\RequestLoggerChannel;
use Dotsplatform\RequestsLogger\LaravelLogger\LaravelProviderRequestsLogger;
use Dotsplatform\RequestsLogger\OpensearchLogger\OpensearchProviderRequestsLogger;
use Illuminate\Support\ServiceProvider;
use OpenSearch\Client;
use OpenSearch\ClientBuilder;

class ProviderRequestLoggerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->bindLoggerClient();
    }

    private function bindLoggerClient(): void
    {
        $this->app->singleton(ProviderRequestsLogger::class, function () {
            $channel = config('requests-logger.default');
            if ($channel === RequestLoggerChannel::SYSTEM) {
                return app(LaravelProviderRequestsLogger::class);
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
