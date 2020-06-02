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

namespace Tenancy\Tests\Identification\Http\Unit;

use Tenancy\Identification\Drivers\Http\Contracts\IdentifiesByHttp;
use Tenancy\Identification\Drivers\Http\Providers\IdentificationProvider;
use Tenancy\Tests\Identification\DriverTestCase;

class HttpDriverTest extends DriverTestCase
{
    protected $provider = IdentificationProvider::class;

    protected $drivers = [
        IdentifiesByHttp::class,
    ];
}
