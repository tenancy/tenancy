<?php

namespace Tenancy\Misc\Help\Data\Packages\Affects;

use Tenancy\Misc\Help\Data\Packages\AffectsPackage;

class Mails extends AffectsPackage {
    protected $events = [
        'Events\\ConfigureMails'
    ];
}
