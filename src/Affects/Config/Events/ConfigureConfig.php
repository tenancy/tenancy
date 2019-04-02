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

namespace Tenancy\Affects\Config\Events;

use Tenancy\Identification\Events\Resolved;
use Tenancy\Identification\Events\Switched;

class ConfigureConfig
{
    /**
     * @var Resolved|Switched
     */
    public $event;
    public $config;

    public function __construct($event, $config)
    {
        $this->event = $event;
        $this->config = $config;
    }
}
