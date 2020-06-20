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

abstract class HooksPackage extends SubPackage
{
    protected $hooks = [];

    protected $providers = [
        'Provider',
    ];

    public function getHooks()
    {
        return array_map(function (string $event) {
            return $this->getNamespace().'\\Hooks\\'.$event;
        }, $this->events);
    }

    public function getSubsection(): string
    {
        return 'Hooks';
    }
}
