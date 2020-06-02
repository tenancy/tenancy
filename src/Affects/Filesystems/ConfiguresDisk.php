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

namespace Tenancy\Affects\Filesystems;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Filesystem\FilesystemManager;
use Tenancy\Affects\Affect;
use Tenancy\Concerns\DispatchesEvents;

class ConfiguresDisk extends Affect
{
    use DispatchesEvents;

    public function fire(): void
    {
        /** @var Factory|FilesystemManager $manager */
        $manager = resolve(Factory::class);

        /** @var Repository $config */
        $config = resolve(Repository::class);

        $diskConfig = [];

        $this->events()->dispatch(new Events\ConfigureDisk($this->event, $diskConfig));

        // Configure the tenant disk.
        $config->set('filesystems.disks.tenant', $diskConfig ?? null);

        // This demands a reload of the disk.
        $manager->forgetDisk('tenant');
    }
}
