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
use Illuminate\Support\ServiceProvider;
use Tenancy\Database\Events\Resolving;

abstract class DatabaseProvider extends ServiceProvider
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
        if ($this->listener) {
            Event::listen(Resolving::class, $this->listener);
        }

        $this->publishConfigs();
    }
}
