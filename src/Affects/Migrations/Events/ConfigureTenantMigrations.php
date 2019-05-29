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

use Illuminate\Database\ConnectionResolverInterface;
use InvalidArgumentException;
use Tenancy\Affects\Models\Database\ConnectionResolver;
use Tenancy\Environment;
use Tenancy\Identification\Events\Switched;
use Tenancy\Tenant\Events\Created;
use Tenancy\Tenant\Events\Deleted;
use Tenancy\Tenant\Events\Updated;

class ConfigureTenantMigrations
{
    /**
     * @var Created|Updated|Deleted
     */
    public $event;
    /**
     * @var array
     */
    public $paths;
    /**
     * @var array
     */
    public $options;

    public function __construct($event, array &$paths = [], array &$options = [])
    {
        $this->event = $event;
        $this->paths = &$paths;
        $this->options = &$options;
    }

    /**
     * @param string|array $paths
     * @return $this
     */
    public function addPaths($paths)
    {
        $this->paths = array_merge(
            $this->paths,
            (array) $paths
        );

        return $this;
    }
}
