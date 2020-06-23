<?php

namespace Tenancy\Misc\Wingman\CLI\Interactions;

class Page extends BaseInteraction
{
    /**
     * Determines if this interaction should react to the provided interaction.
     *
     * @param string $interaction
     *
     * @return boolean
     */
    public function shouldReact(string $interaction): bool
    {
        return is_numeric($interaction);
    }
}
