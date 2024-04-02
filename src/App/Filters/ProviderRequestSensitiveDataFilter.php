<?php
/**
 * Description of ProviderRequestSensitiveDataFilter.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Yehor Herasymchuk <yehor@dotsplatform.com>
 */

namespace Dotsplatform\RequestsLogger\Filters;

use Dotsplatform\RequestsLogger\DTO\ProviderRequestDTO;

class ProviderRequestSensitiveDataFilter
{
    public function __construct(
        private readonly SensitiveDataFilter $sensitiveDataFilter,
    ) {
    }

    public function filter(ProviderRequestDTO $dto): ProviderRequestDTO
    {
        return ProviderRequestDTO::fromArray(
            $this->sensitiveDataFilter->filter($dto->toArray()),
        );
    }
}
