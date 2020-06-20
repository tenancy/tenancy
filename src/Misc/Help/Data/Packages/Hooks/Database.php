<?php

namespace Tenancy\Misc\Help\Data\Packages\Hooks;

use Tenancy\Misc\Help\Data\Packages\HooksPackage;

class Database extends HooksPackage
{
    /** @var array */
    protected $hooks = [
        "DatabaseMutation"
    ];

    /** @var array */
    protected $events = [
        'Events\\ConfigureDatabaseMutation',
        'Events\\Identified',
        'Events\\Resolved',
        'Events\\Resolving',
        'Events\\Drivers\\Configuring'
    ];

    /** @var array */
    protected $requiredEvents = [
        'Events\\Resolving',
        'Events\\Drivers\\Configuring'
    ];

    /** @var array */
    protected $recommendedEvents = [
        'Events\\ConfigureDatabaseMutation'
    ];
}
