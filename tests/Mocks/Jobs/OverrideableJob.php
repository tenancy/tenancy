<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Mocks\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Tenancy\Facades\Tenancy;
use Tenancy\Identification\Contracts\Tenant;

class OverrideableJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /** @var Tenant */
    public $tenant;

    /** @var Collection */
    public $publicCollection;

    /** @var Collection */
    protected $protectedCollection;

    /** @var Collection */
    private $privateCollection;

    /** @var Collection */
    public $anotherPublicCollection;

    /** @var Collection */
    protected $anotherProtectedCollection;

    /** @var Collection */
    private $anotherPrivateCollection;

    public function __construct(
        Tenant $tenant = null,
        Collection $collection = null,
        Collection $anotherCollection = null
    ) {
        $this->tenant = $tenant;

        $this->publicCollection = $collection;
        $this->protectedCollection = $collection;
        $this->priveCollection = $collection;

        $this->anotherPublicCollection = $anotherCollection;
        $this->anotherProtectedCollection = $anotherCollection;
        $this->anotherPriveCollection = $anotherCollection;
    }

    public function handle()
    {
        event('mock.tenant.job', [
            'tenant' => Tenancy::getTenant(),
        ]);
    }
}
