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

namespace Tenancy\Affects;

use Tenancy\Contracts\AffectsApp;
use Tenancy\Identification\Events\Switched;
use Tenancy\Pipeline\Step;

abstract class Affect extends Step implements AffectsApp
{
    public function fires(): bool
    {
        return $this->event instanceof Switched;
    }
}
