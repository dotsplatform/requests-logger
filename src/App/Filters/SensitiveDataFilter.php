<?php
/**
 * Description of SensitiveDataFilter.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Yehor Herasymchuk <yehor@dotsplatform.com>
 */

namespace Dotsplatform\RequestsLogger\Filters;

use Illuminate\Support\Str;

class SensitiveDataFilter
{
    public const SECRET = 'secret';

    public function filter(array $data): array
    {
        foreach ($data as $key => $value) {
            if ($this->isSensitiveKey($key)) {
                $data[$key] = self::SECRET;
                continue;
            }

            if (is_null($value)) {
                continue;
            }

            if (is_array($value)) {
                $data[$key] = $this->filter($value);
            } elseif ($this->isUrl($value)) {
                $data[$key] = $this->cleanUrlParams($value);
            }
        }

        return $data;
    }

    private function isUrl(string $key): bool
    {
        return Str::startsWith($key, [
            'http://',
            'https://',
        ]);
    }

    private function cleanUrlParams(string $url): string
    {
        $parsedUrl = parse_url($url);
        $queryParams = [];
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
        }
        $queryParams = $this->filter($queryParams);
        $updatedQuery = http_build_query($queryParams);

        return $parsedUrl['scheme'].'://'.
            $parsedUrl['host'].
            (isset($parsedUrl['port']) ? ':'.$parsedUrl['port'] : '').
            ($parsedUrl['path'] ?? '').
            ($updatedQuery ? '?'.$updatedQuery : '').
            (isset($parsedUrl['fragment']) ? '#'.$parsedUrl['fragment'] : '');
    }

    private function isSensitiveKey(string $key): bool
    {
        return Str::contains(Str::lower($key), $this->sensitiveKeys());
    }

    private function sensitiveKeys(): array
    {
        $clientSensitiveKeys = config('requests-logger.sensitive_keys', []);
        if (! is_array($clientSensitiveKeys)) {
            return $this->getBaseSensitiveKeys();
        }

        return array_merge($this->getBaseSensitiveKeys(), $clientSensitiveKeys);
    }

    private function getBaseSensitiveKeys(): array
    {
        return [
            'name',
            'email',
            'key',
            'apiKey',
            'password',
            'secret',
        ];
    }
}
