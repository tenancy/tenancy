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

namespace Tenancy\Tests\Affects\Broadcasts\Unit;

use Tenancy\Affects\Broadcasts\Events\ConfigureBroadcast;
use Tenancy\Affects\Broadcasts\Provider;
use Tenancy\Tests\Affects\AffectsEventUnitTestCase;

class ConfigureBroadcastsTest extends AffectsEventUnitTestCase
{
    protected $affectsProvider = Provider::class;

    protected $event = ConfigureBroadcast::class;
}
