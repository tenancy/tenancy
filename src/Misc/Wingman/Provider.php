<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Misc\Wingman;

use Tenancy\Misc\Wingman\CLI\Commands;
use Tenancy\Support\Provider as SupportProvider;

class Provider extends SupportProvider
{
    public function register()
    {
        parent::register();

        $this->commands([
            Commands\Created::class,
            Commands\Updated::class,
            Commands\Deleted::class,
            Commands\ListCommand::class,
        ]);
    }
}
