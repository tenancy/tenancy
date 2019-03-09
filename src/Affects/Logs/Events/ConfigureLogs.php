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

namespace Tenancy\Affects\Logs\Events;

use Tenancy\Identification\Events\Resolved;

class ConfigureLogs
{
    /**
     * @var Resolved
     */
    public $resolved;
    /**
     * @var array
     */
    public $config;

    public function __construct(Resolved $resolved, array &$config = [])
    {
        $this->resolved = $resolved;
        $this->config = $config;
    }
}
