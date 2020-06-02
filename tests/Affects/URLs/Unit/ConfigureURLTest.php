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

namespace Tenancy\Tests\Affects\URLs\Unit;

use Illuminate\Contracts\Routing\UrlGenerator;
use Tenancy\Affects\URLs\Events\ConfigureURL;
use Tenancy\Affects\URLs\Provider;
use Tenancy\Tests\Affects\AffectsEventUnitTestCase;

class ConfigureURLTest extends AffectsEventUnitTestCase
{
    protected $affectsProvider = Provider::class;

    protected $event = ConfigureURL::class;

    protected $eventContains = [
        'url' => UrlGenerator::class,
    ];
}
