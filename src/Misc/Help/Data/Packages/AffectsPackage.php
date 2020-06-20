<?php

namespace Tenancy\Misc\Help\Data\Packages;

abstract class AffectsPackage extends SubPackage
{
    public function getSubsection(): string
    {
        return 'Affects';
    }
}
