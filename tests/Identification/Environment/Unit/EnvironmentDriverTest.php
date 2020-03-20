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

namespace Tenancy\Identification\Environment\Unit;

use Tenancy\Identification\Drivers\Environment\Contracts\IdentifiesByEnvironment;
use Tenancy\Identification\Drivers\Environment\Providers\IdentificationProvider;
use Tenancy\Tests\Identification\DriverTestCase;

class EnvironmentDriverTest extends DriverTestCase
{
    protected $provider = IdentificationProvider::class;

    protected $drivers = [
        IdentifiesByEnvironment::class,
    ];
}
