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

class DatabasePackage extends SubPackage
{
    protected $events = [
        'Events\\Resolving',
        'Events\\Drivers\\Configuring',
    ];

    public function getNamespace()
    {
        return 'Tenancy\\Database\\Driver\\'.$this->getPurpose();
    }

    public function getSubsection(): string
    {
        return 'DbDriver';
    }

    public function formatEvents(array $events)
    {
        return array_map(function (string $event) {
            return 'Tenancy\\Hooks\\Database\\'.$event;
        }, $events);
    }
}
