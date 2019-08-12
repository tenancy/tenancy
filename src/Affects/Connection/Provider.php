<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Laravel Tenancy & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Affects\Connection;

use Tenancy\Support\AffectsProvider;
use Tenancy\Providers\Provides\ProvidesBindings;
use Tenancy\Affects\Connection\Contracts\ResolvesConnections;

class Provider extends AffectsProvider
{
    use ProvidesBindings;

    protected $affects = [ConfiguresConnection::class];

    public $singletons = [
        ResolvesConnections::class => ConnectionResolver::class,
    ];
}
