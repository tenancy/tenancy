<?php

namespace Tenancy\Misc\Help\Data\Packages;

abstract class HooksPackage extends SubPackage
{
    protected $hooks = [];

    protected $providers = [
        'Provider'
    ];

    public function getHooks()
    {
        return array_map(function (string $event){
            return $this->getNamespace() . '\\Hooks\\' . $event;
        }, $this->events);
    }

    public function getSubsection(): string
    {
        return 'Hooks';
    }
}
