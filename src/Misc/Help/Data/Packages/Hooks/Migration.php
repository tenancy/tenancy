<?php

namespace Tenancy\Misc\Help\Data\Packages\Hooks;

use Tenancy\Misc\Help\Data\Packages\HooksPackage;

class Migration extends HooksPackage
{
    /** @var array */
    protected $hooks = [
        "MigratesHook",
        "SeedsHook"
    ];

    /** @var array */
    protected $events = [
        'Events\\ConfigureMigrations',
        'Events\\ConfigureSeeds',
    ];

    /** @var array */
    protected $requiredEvents = [
        'Events\\ConfigureMigrations',
    ];
}
