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

namespace Tenancy\Misc\Help;

use Tenancy\Misc\Help\Contracts\ResolvesPackages;
use Tenancy\Misc\Help\Data\Contracts\Package;

class PackageResolver implements ResolvesPackages
{
    private $packages;

    public function __construct(array $packages = [])
    {
        $this->packages = collect($packages);
    }

    public function registerPackage(Package $package): void
    {
        $this->packages->add($package);
    }

    public function getPackages(): \Illuminate\Support\Collection
    {
        return $this->packages;
    }
}
