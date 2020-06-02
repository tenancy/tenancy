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

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MockSeeder extends Seeder
{
    public function run()
    {
        DB::table('mocks')->insert([
            'id' => 5, 'name' => 'test',
        ]);
    }
}
