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

namespace Tenancy\Misc\Help\Data\Packages;

use Illuminate\Support\Facades\App;
use Tenancy\Identification\Contracts\ResolvesTenants;

class Framework extends ComposerPackage
{
    protected $providers = [
        'Providers\TenancyProvider',
    ];

    public function getNamespace()
    {
        return parent::getNamespace();
    }

    public function isConfigured(): bool
    {
        if (App::make(ResolvesTenants::class)->getModels()->isEmpty()) {
            return false;
        }

        return true;
    }
}
