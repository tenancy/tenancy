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

namespace Tenancy\Tests\Affects\Filesystems\Unit;

use Tenancy\Affects\Filesystems\Events\ConfigureDisk;
use Tenancy\Affects\Filesystems\Provider;
use Tenancy\Tests\Affects\AffectsEventUnitTestCase;

class ConfigureDiskTest extends AffectsEventUnitTestCase
{
    protected $affectsProvider = Provider::class;

    protected $event = ConfigureDisk::class;
}
