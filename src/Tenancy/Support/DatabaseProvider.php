<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Laravel Tenancy & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Support;

use Illuminate\Support\Facades\Event;
use Tenancy\Affects\Connection\Events as Connection;
use Tenancy\Hooks\Database\Events as Database;

abstract class DatabaseProvider extends Provider
{
    use Concerns\PublishesConfigs;

    /**
     * Listener for the resolving event.
     *
     * @var string
     */
    protected $listener;

    public function register()
    {
        parent::register();
        if ($this->listener) {
            Event::listen(Database\Resolving::class, $this->listener);
        }
        if ($this->connectionListener) {
            Event::listen(Connection\Resolving::class, $this->connectionListener);
        }

        $this->publishConfigs();
    }
}
