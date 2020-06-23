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

namespace Tenancy\Misc\Wingman\CLI\Interactions;

class Page extends BaseInteraction
{
    /**
     * Determines if this interaction should react to the provided interaction.
     *
     * @param string $interaction
     *
     * @return bool
     */
    public function shouldReact(string $interaction): bool
    {
        return is_numeric($interaction);
    }
}
