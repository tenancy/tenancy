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

namespace Tenancy\Tests\Hooks\Database\Unit\Events\Drivers;

use Tenancy\Hooks\Database\Events\ConfigureDatabaseMutation;
use Tenancy\Hooks\Database\Hooks\DatabaseMutation;
use Tenancy\Hooks\Database\Provider as DatabaseProvider;
use Tenancy\Tests\Hooks\ConfigureHookTestCase;

class ConfigureDatabaseMutationTest extends ConfigureHookTestCase
{
    protected $additionalProviders = [DatabaseProvider::class];

    protected $hookClass = DatabaseMutation::class;

    protected $eventClass = ConfigureDatabaseMutation::class;
}
