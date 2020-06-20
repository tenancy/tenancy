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

namespace Tenancy\Misc\Help\Data;

use Illuminate\Support\Collection;
use Tenancy\Misc\Help\Data\Packages\Affects;
use Tenancy\Misc\Help\Data\Packages as Main;
use Tenancy\Misc\Help\Data\Packages\Hooks;

class Packages extends Collection
{
    public function __construct()
    {
        $this->items = $this->getArrayableItems([
            new Main\Framework(),

            // All affects
            new Affects\Broadcasts(),
            new Affects\Cache(),
            new Affects\Configs(),
            new Affects\Connections(),
            new Affects\Filesystems(),
            new Affects\Logs(),
            new Affects\Mails(),
            new Affects\Models(),
            new Affects\Routes(),
            new Affects\Urls(),
            new Affects\Views(),

            // All Hooks
            new Hooks\Database(),
            new Hooks\Migration(),
        ]);
    }
}
