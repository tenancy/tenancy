<?php

namespace Tenancy\SDK;

use Tenancy\Support\Concerns\PublishesConfigs;
use Tenancy\Support\Provider as SupportProvider;

abstract class Provider extends SupportProvider {
    use PublishesConfigs;

    public function register() {
        parent::register();

        $this->publishConfigs();
    }
}

