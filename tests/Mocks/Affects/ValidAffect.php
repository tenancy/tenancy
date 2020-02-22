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

namespace Tenancy\Tests\Mocks\Affects;

use Tenancy\Contracts\AffectsApp;

class ValidAffect implements AffectsApp
{
    public function fire(): void
    {
        // TODO: Implement fire method
    }

    public function priority(): int
    {
        return 0;
    }

    public function fires(): bool
    {
        return true;
    }

    public function for($event)
    {
        // TODO: Implement for method
    }
}
