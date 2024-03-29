<?php
/**
 * Description of ParamsSanitizer.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Yehor Herasymchuk <yehor@dotsplatform.com>
 */

namespace Dotsplatform\RequestsLogger\OpensearchLogger\Repositories;

class ParamsSanitizer
{
    public function clean(string $param): string
    {
        return htmlspecialchars($param);
    }
}
