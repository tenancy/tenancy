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

use Tenancy\Facades\Tenancy;

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

Route::get('test', function () {
    return 'test';
})->name('test');

Route::group(['prefix' => 'nested'], function () {
    Route::get('test', function () {
        return 'nested.test';
    })->name('nested.test');

    Route::get('', function () {
        return 'nested';
    })->name('nested');
});
