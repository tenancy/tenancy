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

namespace Tenancy\SDK;

use Tenancy\Support\Concerns\PublishesConfigs;
use Tenancy\Support\Provider as SupportProvider;

abstract class Provider extends SupportProvider
{
    use PublishesConfigs;

    public function register()
    {
        parent::register();

        $this->publishConfigs();
    }
}
