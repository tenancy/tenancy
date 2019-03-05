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

use Tenancy\Tenant\Events\Created;

class AutoCreation extends DatabaseMutation
{
    /**
     * @param Created $event
     * @return array|null
     */
    public function statements($event): ?array
    {
        if ($this->driver && config('tenancy.database.auto-create')) {
            return $this->driver->create($event->tenant);
        }

        return null;
    }
}
