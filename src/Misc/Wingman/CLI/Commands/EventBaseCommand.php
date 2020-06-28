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

namespace Tenancy\Misc\Wingman\CLI\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Contracts\Tenant;

abstract class EventBaseCommand extends Command
{
    /** @var string */
    protected $event;

    /** @var Dispatcher */
    protected $dispatcher;

    /** @var Resolver */
    protected $resolver;

    /**
     * Constructs a new instance.
     *
     * @param Dispatcher      $dispatcher
     * @param ResolvesTenants $resolver
     */
    public function __construct(Dispatcher $dispatcher, ResolvesTenants $resolver)
    {
        parent::__construct();
        $this->dispatcher = $dispatcher;
        $this->resolver = $resolver;
    }

    /**
     * Performs the command.
     *
     * @return void
     */
    public function handle()
    {
        $this->resolver->getModels()->each(function (string $class) {
            $model = (new $class());

            if ($this->option('tenant-identifiers') && !in_array($model->getTenantIdentifier(), $this->parseArrayOption('tenant-identifiers'))) {
                return;
            }

            $this->info('Triggering event for tenants with identifier: '.$model->getTenantIdentifier());

            $model->newQuery()->orderBy($model->getTenantKeyName())->chunk($this->option('chunk'), function (Collection $tenants) {
                $tenants->each(function (Tenant $tenant) {
                    if ($this->option('tenants')) {
                        if (!in_array($tenant->getTenantKey(), $this->parseArrayOption('tenants'))) {
                            return;
                        }
                    }

                    $this->info('Triggering event for tenant with key: '.$tenant->getTenantKey());
                    $event = $this->event;
                    $this->dispatcher->dispatch(new $event($tenant));
                });
            });
        });
    }

    /**
     * Parses the given option key to an array.
     *
     * @param string $key
     *
     * @return array
     */
    private function parseArrayOption(string $key)
    {
        return Arr::wrap(explode(',', $this->option($key)));
    }
}
