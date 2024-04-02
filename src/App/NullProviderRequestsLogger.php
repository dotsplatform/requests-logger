<?php
/**
 * Description of LaravelLogger.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Yehor Herasymchuk <yehor@dotsplatform.com>
 */

namespace Dotsplatform\RequestsLogger;

use Dotsplatform\RequestsLogger\DTO\ProviderRequestDTO;

class NullProviderRequestsLogger implements ProviderRequestsLogger
{
    public function log(ProviderRequestDTO $dto): void
    {
    }
}
