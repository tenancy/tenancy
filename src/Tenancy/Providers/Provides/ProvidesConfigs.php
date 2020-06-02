<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Providers\Provides;

trait ProvidesConfigs
{
    protected function registerProvidesConfigs()
    {
        foreach ($this->configs as $config) {
            $configPath = basename($config);
            $configName = basename($config, '.php');

            $this->publishes([$config => config_path('tenancy'.DIRECTORY_SEPARATOR.$configPath)], [$configName, 'tenancy']);

            $this->mergeConfigFrom($config, 'tenancy.'.$configName);
        }
    }
}
