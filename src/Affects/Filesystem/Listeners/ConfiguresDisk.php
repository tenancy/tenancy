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

namespace Tenancy\Affects\Filesystem\Listeners;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Filesystem\FilesystemManager;
use Tenancy\Affects\Filesystem\Events\ConfigureDisk;
use Tenancy\Identification\Events\Resolved;

class ConfiguresDisk
{
    public function handle(Resolved $event)
    {
        /** @var Factory|FilesystemManager $manager */
        $manager = resolve(Factory::class);
        /** @var Repository $config */
        $config = resolve(Repository::class);
        /** @var Dispatcher $events */
        $events = resolve(Dispatcher::class);

        if ($event->tenant) {
            $diskConfig = [];
            $events->until(new ConfigureDisk($event, $diskConfig));
        }

        // Configure the tenant disk.
        $config->set('filesystems.disks.tenant', $diskConfig ?? null);

        // This demands a reload of the disk.
        $manager->set('tenant', null);
    }
}
