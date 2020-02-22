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

namespace Tenancy\Tests\Affects\Views;

trait UsesViews
{
    /**
     * Returns the path of the views used for this trait.
     *
     * @return string
     */
    protected function getViewsPath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'views';
    }
}
