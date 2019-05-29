<?php

/*
 * This file is part of the tenancy/tenancy package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Affects\Migrations\Events;

use Tenancy\Affects\Migrations\Hooks\MigratesHook;
use Tenancy\Tenant\Events\Event;

class ConfigureTenantMigrations
{
    /**
     * @var Event
     */
    public $event;

    /**
     * @var MigratesHook
     */
    public $hook;

    public function __construct(Event $event, MigratesHook $hook)
    {
        $this->event = $event;
        $this->hook = $hook;
    }

    public function path(string $path)
    {
        $this->hook->migrator->path($path);

        return $this;
    }
}
