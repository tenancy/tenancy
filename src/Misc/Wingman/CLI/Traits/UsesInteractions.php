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

namespace Tenancy\Misc\Wingman\CLI\Traits;

use Tenancy\Misc\Wingman\CLI\Contracts\Interaction;

trait UsesInteractions
{
    /** @var string */
    protected $interaction = '';

    /** @var array */
    protected $interactions = [];

    /**
     * Registers an interaction into the class.
     *
     * @param Interaction $interaction
     * @param string      $function
     *
     * @return void
     */
    protected function registerInteraction(Interaction $interaction)
    {
        $this->interactions[] = $interaction;
    }

    /**
     * Sets the interactions.
     *
     * @param array $interactions
     *
     * @return void
     */
    protected function setInteractions(array $interactions)
    {
        $this->interactions = $interactions;
    }

    /**
     * Gets the function that should be used based on the interaction.
     *
     * @param string $interaction
     *
     * @return string|void
     */
    protected function getInteractionFunction(string $interaction)
    {
        foreach ($this->interactions as $interactionInstance) {
            if ($interactionInstance->shouldReact($interaction)) {
                return 'interact'.$interactionInstance->getName();
            }
        }

        return null;
    }

    /**
     * Interacts based on the provided interaction.
     *
     * @param string $interaction
     *
     * @return void
     */
    protected function triggerInteraction(string $interaction)
    {
        $function = $this->getInteractionFunction($interaction);

        if (is_null($function)) {
            return;
        }

        if (!method_exists($this, $function)) {
            return;
        }

        $this->{$function}($interaction);
    }

    /**
     * Returns the interaction.
     *
     * @return string
     */
    protected function getInteraction()
    {
        return $this->interaction;
    }

    /**
     * Sets the interaction.
     *
     * @param string $interaction
     *
     * @return void
     */
    protected function setInteraction(string $interaction = '')
    {
        $this->interaction = $interaction;
    }

    /**
     * Gets the interactions as human readable array.
     *
     * @return array
     */
    protected function getInteractionsAsHuman()
    {
        return array_map(function (Interaction $interaction) {
            return $interaction->getShortcut().': '.$interaction->getName();
        }, $this->interactions);
    }
}
