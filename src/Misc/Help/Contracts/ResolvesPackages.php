<?php

namespace Tenancy\Misc\Help\Contracts;

use Illuminate\Support\Collection;

interface ResolvesPackages
{
    public function getPackages(): Collection;
    public function registerPackage(Package $package): void;
}
