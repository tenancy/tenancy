<?php

namespace Tenancy\Misc\Help\Data\Packages\Affects;

use Tenancy\Misc\Help\Data\Packages\AffectsPackage;

class Broadcasts extends AffectsPackage {
    protected $events = [
        'Events\\ConfigureBroadcasts'
    ];
}
