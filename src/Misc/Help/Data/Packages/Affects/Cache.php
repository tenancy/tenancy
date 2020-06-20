<?php

namespace Tenancy\Misc\Help\Data\Packages\Affects;

use Tenancy\Misc\Help\Data\Packages\AffectsPackage;

class Cache extends AffectsPackage {
    protected $events = [
        'Events\\ConfigureCache'
    ];
}
