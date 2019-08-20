<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Laravel Tenancy & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Providers\Provides;

trait ProvidesConfig
{
    protected function registerProvidesConfig()
    {
        foreach ($this->configs as $path => $key) {
            $this->mergeConfigFrom(
                $path,
                $key
            );
        }

        $this->addPublishGroup('tenancy', $this->formatConfigsForPublishGroup($this->configs));
    }

    protected function formatConfigsForPublishGroup(array $array)
    {
        $formatted = [];
        foreach ($array as $path => $key) {
            $formatted += [$path => config_path($key.'.php')];
        }

        return $formatted;
    }
}
