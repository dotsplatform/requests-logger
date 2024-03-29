<?php
/**
 * Description of RequestLogger.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Yehor Herasymchuk <yehor@dotsplatform.com>
 */

namespace Dotsplatform\RequestsLogger;

use Dotsplatform\RequestsLogger\DTO\ProviderRequestDTO;

interface ProviderRequestsLogger
{
    public function log(
        ProviderRequestDTO $dto,
    ): void;
}
