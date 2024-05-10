<?php
/**
 * Description of Job.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dotsplatform\RequestsLogger\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

abstract class Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public const MAX_SIZE_KB = 100;
}
