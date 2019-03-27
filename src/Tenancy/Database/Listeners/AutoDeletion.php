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

use Tenancy\Tenant\Events\Deleted;

class AutoDeletion
{
    /**
     * @param Deleted $event
     * @return array|null
     */
    public function statements($event): ?bool
    {
        if ($this->driver && config('tenancy.database.auto-delete')) {
            return $this->driver->delete($event->tenant);
        }

        return null;
    }
}
