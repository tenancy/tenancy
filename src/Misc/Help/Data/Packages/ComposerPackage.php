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
use Illuminate\Support\Str;
use Tenancy\Misc\Help\Contracts\Package;

abstract class ComposerPackage implements Package
{
    protected $providers = [];

    public function getName(): string
    {
        return 'tenancy/'.$this->getPackageName();
    }

    public function isRegistered(): bool
    {
        foreach ($this->getProviders() as $provider) {
            if (empty(App::getProviders($provider))) {
                return false;
            }
        }

        return true;
    }

    public function isInstalled(): bool
    {
        foreach ($this->getProviders() as $provider) {
            if (!class_exists($provider)) {
                return false;
            }
        }

        return true;
    }

    protected function getPackageName()
    {
        return Str::kebab(basename(get_class($this)));
    }

    protected function getNamespace()
    {
        return 'Tenancy';
    }

    protected function getProviders()
    {
        return array_map(function (string $event) {
            return $this->getNamespace().'\\'.$event;
        }, $this->providers);
    }
}
