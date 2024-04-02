<?php
/**
 * Description of ESProviderRequestsLogger.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Yehor Herasymchuk <yehor@dotsplatform.com>
 */

namespace Dotsplatform\RequestsLogger\OpensearchLogger;

use Dotsplatform\RequestsLogger\DTO\ProviderRequestDTO;
use Dotsplatform\RequestsLogger\OpensearchLogger\Repositories\OpensearchProviderRequestsRepository;
use Dotsplatform\RequestsLogger\ProviderRequestsLogger;

class OpensearchProviderRequestsLogger implements ProviderRequestsLogger
{
    public function __construct(
        private readonly OpensearchProviderRequestsRepository $repository,
    ) {
    }

    public function log(ProviderRequestDTO $dto): void
    {
        $this->repository->store($dto);
    }
}
