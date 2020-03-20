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

namespace Tenancy\Tests\Affects\Configs\Unit;

use Illuminate\Contracts\Config\Repository;
use Tenancy\Affects\Configs\Events\ConfigureConfig;
use Tenancy\Affects\Configs\Provider;
use Tenancy\Tests\Affects\AffectsEventUnitTestCase;

class ConfigureConfigTest extends AffectsEventUnitTestCase
{
    protected $affectsProvider = Provider::class;

    protected $event = ConfigureConfig::class;

    protected $eventContains = [
        'config' => Repository::class,
    ];
}
