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

namespace Tenancy\Providers\Provides;

trait ProvidesConfig
{
    protected $configs = [
        __DIR__ . '/../../resources/config/tenancy.php' => 'tenancy',
    ];

    protected function registerProvidesConfig()
    {
        foreach ($this->configs as $path => $key) {
            $this->mergeConfigFrom(
                $path,
                $key
            );
        }

        $this->addPublishGroup('tenancy', array_keys($this->configs));
    }
}
