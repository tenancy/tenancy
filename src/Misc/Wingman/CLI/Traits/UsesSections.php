<?php

namespace Tenancy\Misc\Wingman\CLI\Traits;

trait UsesSections
{
    public function createSection()
    {
        return $this->symfonyOutput->section();
    }
}
