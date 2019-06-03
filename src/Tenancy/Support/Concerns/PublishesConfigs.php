<?php

/*
 * This file is part of the tenancy/tenancy package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

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
