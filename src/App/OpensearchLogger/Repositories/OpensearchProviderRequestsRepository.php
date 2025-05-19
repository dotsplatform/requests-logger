<?php
/**
 * Description of CreateProviderRequestsIndex.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Yehor Herasymchuk <yehor@dotsplatform.com>
 */

namespace Dotsplatform\RequestsLogger\OpensearchLogger\Repositories;

use Dotsplatform\RequestsLogger\DTO\ProviderRequestDTO;
use Illuminate\Support\Carbon;
use OpenSearch\Client;
use RuntimeException;

class OpensearchProviderRequestsRepository
{
    public function __construct(
        private readonly Client $client,
    ) {
    }

    public function createIndex(): void
    {
        $indexName = $this->getIndexName();

        $this->client->indices()->create([
            'index' => $indexName,
            'body' => [
                'mappings' => [
                    'properties' => [
                        'env' => [
                            'type' => 'keyword',
                        ],
                        'trace_id' => [
                            'type' => 'keyword',
                        ],
                        'created_time' => [
                            'type' => 'date',
                            'format' => 'epoch_millis',
                        ],
                        'account' => [
                            'type' => 'keyword',
                        ],
                        'provider' => [
                            'type' => 'keyword',
                        ],
                        'providerMethod' => [
                            'type' => 'keyword',
                        ],
                        'method' => [
                            'type' => 'keyword',
                        ],
                        'url' => [
                            'type' => 'keyword',
                        ],
                        'body' => [
                            'type' => 'text',
                        ],
                        'headers' => [
                            'type' => 'object',
                        ],
                        'response_code' => [
                            'type' => 'integer',
                        ],
                        'response_body' => [
                            'type' => 'text',
                        ],
                        'duration_ms' => [
                            'type' => 'integer',
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function deleteIndex(): void
    {
        $indexName = $this->getIndexName();

        $this->client->indices()->delete([
            'index' => $indexName,
        ]);
    }

    public function store(ProviderRequestDTO $dto): void
    {
        $params = [
            'index' => $this->getIndexName(),
            'id' => $dto->getId(),
            'body' => [
                'trace_id' => $dto->getTraceId(),
                'entity_id' => $dto->getEntityId(),
                'account' => $dto->getAccount(),
                'env' => $dto->getEnv(),
                'provider' => $dto->getProvider(),
                'providerMethod' => $dto->getProviderMethod(),
                'created_time' => $dto->getCreatedTime(),
                'method' => $dto->getMethod(),
                'url' => $dto->getUrl(),
                'headers' => $dto->getHeaders(),
                'body' => $dto->getBody(),
                'response_code' => $dto->getResponseCode(),
                'response_body' => $dto->getResponseBody(),
                'duration_ms' => $dto->getDurationMs(),
            ],
        ];

        $this->client->create($params);
    }

    public function deleteOld(Carbon $date): void
    {
        $this->client->deleteByQuery([
            'index' => $this->getIndexName(),
            'body'  => [
                'query' => [
                    'range' => [
                        'created_time' => [
                            'lt' => $date->getTimestampMs(),
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function search(int $limit, int $offset = 0): array
    {
        $indexName = $this->getIndexName();

        return $this->client->search([
            'index' => $indexName,
            'body' => [
                'size' => $limit,
                'from' => $offset,
            ],
        ]);
    }

    private function getIndexName(): string
    {
        $name = config('requests-logger.channels.opensearch.indexes.provider_requests');
        if (! is_string($name)) {
            throw new RuntimeException('Invalid index name');
        }

        return $name;
    }
}
