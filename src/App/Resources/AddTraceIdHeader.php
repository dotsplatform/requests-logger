<?php
/**
 * Description of AddTraceIdHeader.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dotsplatform\RequestsLogger\Resources;

use Closure;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class AddTraceIdHeader
{
    public const TRACE_ID_HEADER = 'X-Trace-Id';

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->header(self::TRACE_ID_HEADER);
        if (! $id) {
            $this->generateId($request);
        }

        return $next($request);
    }

    private function generateId(Request $request): void
    {
        $request->headers->set('X-Trace-Id', Uuid::uuid7()->toString());
    }
}
