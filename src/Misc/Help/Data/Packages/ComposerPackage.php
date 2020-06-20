<?php

namespace Tenancy\Misc\Help\Data\Packages;

use Illuminate\Support\Facades\App;
use Tenancy\Misc\Help\Contracts\Package;
use Illuminate\Support\Str;

abstract class ComposerPackage implements Package {
    protected $providers = [];

    public function getName(): string
    {
        return 'tenancy/' . $this->getPackageName();
    }

    public function isRegistered(): bool{
        foreach ($this->getProviders() as $provider) {
            if(empty(App::getProviders($provider))){
                return false;
            }
        }
        return true;
    }

    public function isInstalled(): bool {
        foreach ($this->getProviders() as $provider) {
            if(!class_exists($provider)){
                return false;
            }
        }
        return true;
    }

    protected function getPackageName() {
        return Str::kebab(basename(get_class($this)));
    }

    protected function getNamespace()
    {
        return "Tenancy";
    }

    protected function getProviders()
    {
        return array_map(function (string $event){
            return $this->getNamespace() . '\\' . $event;
        }, $this->providers);
    }
}
