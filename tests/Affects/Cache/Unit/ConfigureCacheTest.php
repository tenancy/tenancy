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

namespace Tenancy\Tests\Affects\Cache\Unit;

use Tenancy\Affects\Cache\Events\ConfigureCache;
use Tenancy\Affects\Cache\Provider;
use Tenancy\Tests\Affects\AffectsEventUnitTestCase;

class ConfigureCacheTest extends AffectsEventUnitTestCase
{
    protected $affectsProvider = Provider::class;

    protected $event = ConfigureCache::class;
}
