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

namespace Tenancy\Affects\Contracts;

interface ResolvesAffects
{
    public function addAffect($affect);

    public function handle($event, callable $fire = null);

    public function getAffects(): array;

    public function setAffects(array $hooks);
}
