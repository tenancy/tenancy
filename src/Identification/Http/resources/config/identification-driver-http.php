<?php declare(strict_types=1);

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

return [

    /**
     * Whether to initiate tenant identification early.
     *
     * @info This will set up a middleware with high priority to
     * resolve the Environment and run the tenant identification.
     *
     * @var bool
     */
    'eager' => env('TENANCY_EAGER_HTTP_IDENTIFICATION', true),
];
