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

use Tenancy\Misc\Wingman\CLI\Contracts\Interaction;

class BaseInteraction implements Interaction
{
    /**
     * Gets the shortcut key for the interaction.
     *
     * @return string
     */
    public function getShortcut(): string
    {
        return substr($this->getName(), 0, 1);
    }

    /**
     * Gets the name of the shortcut.
     *
     * @return string
     */
    public function getName(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    /**
     * Detects if this interaction is the interaction.
     *
     * @param string $interaction
     *
     * @return bool
     */
    public function shouldReact(string $interaction): bool
    {
        return in_array($interaction, $this->getOptions());
    }

    /**
     * Gets the possible options for an interaction.
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            $this->getShortcut(),
            $this->getName(),
        ];
    }
}
