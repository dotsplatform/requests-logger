<?php
/**
 * Description of ApiRequestEntity.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Yehor Herasymchuk <yehor@dotsplatform.com>
 */

namespace Dotsplatform\RequestsLogger\DTO;

use Dots\Data\Entity;
use Ramsey\Uuid\Uuid;

class ProviderRequestDTO extends Entity
{
    protected string $id;

    protected ?string $trace_id;

    protected string $entityId;

    protected int $created_time;

    protected ?string $env;

    protected ?string $account;

    protected ?string $provider;

    protected ?string $providerMethod;

    protected string $method;

    protected string $url;

    protected array $headers = [];

    protected array $body = [];

    protected array $request_data = [];

    protected int $response_code;

    protected ?array $response_body;

    protected int $duration_ms = 0;

    public static function fromArray(array $data): static
    {
        $data['id'] = $data['id'] ?? Uuid::uuid7()->toString();
        $data['created_time'] = $data['created_time'] ?? time() * 1000;

        return parent::fromArray($data);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTraceId(): ?string
    {
        return $this->trace_id;
    }

    public function getCreatedTime(): int
    {
        return $this->created_time;
    }

    public function getEnv(): ?string
    {
        return $this->env;
    }

    public function getAccount(): ?string
    {
        return $this->account;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function getProviderMethod(): ?string
    {
        return $this->providerMethod;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function getResponseCode(): int
    {
        return $this->response_code;
    }

    public function getResponseBody(): ?array
    {
        return $this->response_body;
    }

    public function getDurationMs(): int
    {
        return $this->duration_ms;
    }

    public function getEntityId(): string
    {
        return $this->entityId;
    }

    public function getRequestData(): array
    {
        return $this->request_data;
    }

}
