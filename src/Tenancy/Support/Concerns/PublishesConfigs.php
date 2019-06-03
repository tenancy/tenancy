<?php

namespace Tenancy\Support\Concerns;

trait PublishesConfigs
{
    /**
     * Configuration files to publish.
     *
     * @var array
     */
    protected $configs = [];

    protected function publishConfigs()
    {
        foreach ($this->configs as $config) {
            $configPath = basename($config);
            $configName = basename($config, '.php');

            $this->publishes([$config => config_path('tenancy' . DIRECTORY_SEPARATOR . $configPath)], [$configName, "tenancy"]);

            $this->mergeConfigFrom($config, 'tenancy.' . $configName);
        }
    }
}