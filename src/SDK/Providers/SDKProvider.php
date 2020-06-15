<?php

namespace Tenancy\SDK\Providers;

use Tenancy\SDK\Provider;

class SDKProvider extends Provider {
    protected $configs = [
        __DIR__ . '/../resources/configs/sdk.php'
    ];
}
