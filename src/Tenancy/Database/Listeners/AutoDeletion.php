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

class AutoDeletion extends DatabaseMutation
{
    public function handle(Deleted $deleted): ?array
    {
        if ($this->driver && config('tenancy.database.auto-delete')) {
            return $this->driver->delete($deleted->tenant);
        }

        return null;
    }
}
