<?php declare(strict_types=1);

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

namespace Tenancy\Database\Listeners;

class AutoDeletion extends DatabaseMutation
{
    public function handle($event): ?bool
    {
        if (config('tenancy.database.auto-delete') && $driver = $this->driver($event->tenant)) {
            return $driver->delete($event->tenant);
        }

        return null;
    }
}
