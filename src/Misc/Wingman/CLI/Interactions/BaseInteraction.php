<?php

namespace Tenancy\Misc\Wingman\CLI\Interactions;

use Tenancy\Misc\Wingman\CLI\Contracts\Interaction;

class BaseInteraction implements Interaction
{
    /**
     * Gets the shortcut key for the interaction
     *
     * @return string
     */
    public function getShortcut(): string
    {
        return substr($this->getName(), 0, 1);
    }

    /**
     * Gets the name of the shortcut
     *
     * @return string
     */
    public function getName(): string
    {
        return basename(get_class($this));
    }

    /**
     * Detects if this interaction is the interaction.
     *
     * @param string $interaction
     *
     * @return boolean
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
            $this->getName()
        ];
    }
}
