<?php

namespace Tenancy\Misc\Help\Data\Packages;

use Illuminate\Support\Facades\App;
use Tenancy\Identification\Contracts\ResolvesTenants;

class Framework extends ComposerPackage {
    protected $providers = [
        'Providers\TenancyProvider'
    ];

    public function getNamespace()
    {
        return parent::getNamespace();
    }

    public function isConfigured(): bool {
        if(App::make(ResolvesTenants::class)->getModels()->isEmpty()){
            return false;
        }

        return true;
    }
}
