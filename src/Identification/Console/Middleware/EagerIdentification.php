<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Identification\Drivers\Console\Middleware;

use Illuminate\Console\Events\CommandStarting;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\Console\Input\InputInterface;
use Tenancy\Environment;

class EagerIdentification
{
    /**
     * @var Application
     */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handle(CommandStarting $event)
    {
        /** @var Environment $tenancy */
        $tenancy = $this->app->make(Environment::class);

        if (!$tenancy->isIdentified()) {
            $this->app->instance(InputInterface::class, $event->input);
            $tenancy->getTenant();
        }
    }
}
