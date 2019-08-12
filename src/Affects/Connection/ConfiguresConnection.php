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

use Tenancy\Affects\Affect;
use Tenancy\Affects\Connection\Contracts\ResolvesConnections;
use Tenancy\Concerns\DispatchesEvents;

class ConfiguresConnection extends Affect
{
    use DispatchesEvents;

    protected $resolver;

    public function __construct(ResolvesConnections $resolver)
    {
        $this->resolver = $resolver;
    }

    public function fire(): void
    {
        $this->resolver->__invoke($this->event->tenant);
    }
}
