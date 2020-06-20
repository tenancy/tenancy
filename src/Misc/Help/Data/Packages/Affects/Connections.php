<?php

namespace Tenancy\Misc\Help\Data\Packages\Affects;

use Tenancy\Misc\Help\Data\Packages\AffectsPackage;

class Connections extends AffectsPackage {

    /** @var array */
    protected $events = [
        'Events\\Identified',
        'Events\\Resolved',
        'Events\\Resolving'
    ];

    /** @var array */
    protected $recommendedEvents = [
        'Events\\Resolving',
        'Events\\Configuring'
    ];
}
