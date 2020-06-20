<?php

namespace Tenancy\Misc\Help\Data\Packages\Affects;

use Tenancy\Misc\Help\Data\Packages\AffectsPackage;

class Routes extends AffectsPackage {
    protected $events = [
        'Events\\ConfigureRoutes'
    ];
}
