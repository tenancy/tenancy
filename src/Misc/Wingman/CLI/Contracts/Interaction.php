<?php

namespace Tenancy\Misc\Wingman\CLI\Contracts;

interface Interaction
{
    public function getShortcut(): string;
    public function getName(): string;
    public function shouldReact(string $interaction): bool;
}
