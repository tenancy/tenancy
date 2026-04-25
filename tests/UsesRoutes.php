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

trait UsesRoutes
{
    /**
     * Returns the path of the mock routes.
     *
     * @return string
     */
    protected function getRoutesPath()
    {
        return __DIR__.DIRECTORY_SEPARATOR.'Mocks'.DIRECTORY_SEPARATOR.'Routes';
    }

    /**
     * Gets the path to the Tenant routes file.
     *
     * @return string
     */
    protected function getTenantRoutesPath()
    {
        return $this->getRoutesPath().DIRECTORY_SEPARATOR.'tenant.php';
    }

    /**
     * Gets the path to the normal routes path.
     *
     * @return string
     */
    protected function getNormalRoutesPath()
    {
        return $this->getRoutesPath().DIRECTORY_SEPARATOR.'routes.php';
    }
}
