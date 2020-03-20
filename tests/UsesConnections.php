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

namespace Tenancy\Tests;

trait UsesConnections
{
    /**
     * Gets the path to the sqlite database configuration.
     *
     * @return string
     */
    protected function getSqliteConfigurationPath()
    {
        return __DIR__.DIRECTORY_SEPARATOR.'Mocks'
            .DIRECTORY_SEPARATOR.'Connections'
            .DIRECTORY_SEPARATOR.'sqlite.php';
    }

    /**
     * Gets the path to the mysql database configuration.
     *
     * @return string
     */
    protected function getMysqlConfigurationPath()
    {
        return __DIR__.DIRECTORY_SEPARATOR.'Mocks'
            .DIRECTORY_SEPARATOR.'Connections'
            .DIRECTORY_SEPARATOR.'mysql.php';
    }
}
