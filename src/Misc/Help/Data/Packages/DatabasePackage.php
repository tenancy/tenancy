<?php

namespace Tenancy\Misc\Help\Data\Packages;

class DatabasePackage extends SubPackage
{

    protected $events = [
        'Events\\Resolving',
        'Events\\Drivers\\Configuring'
    ];

    public function getNamespace()
    {
        return 'Tenancy\\Database\\Driver\\' . $this->getPurpose();
    }

    public function getSubsection(): string
    {
        return 'DbDriver';
    }

    public function formatEvents(array $events)
    {
        return array_map(function (string $event){
            return 'Tenancy\\Hooks\\Database\\' . $event;
        }, $events);
    }
}
