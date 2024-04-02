<?php
/**
 * Description of LoggerType.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Yehor Herasymchuk <yehor@dotsplatform.com>
 */

namespace Dotsplatform\RequestsLogger\DTO;

abstract class RequestLoggerChannel
{
    public const NULL = 'null';

    public const SYSTEM = 'system';

    public const OPENSEARCH = 'opensearch';
}
