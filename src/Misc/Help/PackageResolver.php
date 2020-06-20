<?php

namespace Tenancy\Misc\Help;

use Tenancy\Misc\Help\Contracts\Package;
use Tenancy\Misc\Help\Contracts\ResolvesPackages;

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
