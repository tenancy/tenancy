<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Laravel Tenancy & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Identification\Queue\Mocks;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Tenancy\Facades\Tenancy;

class Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tenant = null;
    public $tenant_key = null;
    public $tenant_identifier = null;

    public function __construct($tenant_key = null, string $tenant_identifier = null)
    {
        $this->tenant_key = $tenant_key;
        $this->tenant_identifier = $tenant_identifier;
    }

    public function handle()
    {
        event('mock.tenant.job', [
            'tenant' => Tenancy::getTenant(),
        ]);
    }
}
