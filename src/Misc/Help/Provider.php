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

namespace Tenancy\Misc\Help;

use Tenancy\Misc\Help\Contracts\ResolvesPackages;
use Tenancy\Misc\Help\Data\Packages\Affects;
use Tenancy\Misc\Help\Data\Packages as Main;
use Tenancy\Misc\Help\Data\Packages\Database;
use Tenancy\Misc\Help\Data\Packages\Hooks;
use Tenancy\Support\Provider as SupportProvider;

class Provider extends SupportProvider
{
    public $singletons = [
        ResolvesPackages::class => PackageResolver::class,
    ];

    public function register()
    {
        parent::register();

        $this->app->resolving(ResolvesPackages::class, function (ResolvesPackages $resolver) {
            $resolver->registerPackage(new Main\Framework());

            // All affects
            $resolver->registerPackage(new Affects\Broadcasts());
            $resolver->registerPackage(new Affects\Cache());
            $resolver->registerPackage(new Affects\Configs());
            $resolver->registerPackage(new Affects\Connections());
            $resolver->registerPackage(new Affects\Filesystems());
            $resolver->registerPackage(new Affects\Logs());
            $resolver->registerPackage(new Affects\Mails());
            $resolver->registerPackage(new Affects\Models());
            $resolver->registerPackage(new Affects\Routes());
            $resolver->registerPackage(new Affects\Urls());
            $resolver->registerPackage(new Affects\Views());

            // All Hooks
            $resolver->registerPackage(new Hooks\Database());
            $resolver->registerPackage(new Hooks\Migration());

            // All Database Drivers
            $resolver->registerPackage(new Database\Mysql());
            $resolver->registerPackage(new Database\Sqlite());

            return $resolver;
        });
    }
}
