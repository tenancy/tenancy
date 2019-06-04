<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MockSeeder extends Seeder
{
    public function run()
    {
        DB::table('mocks')->insert([
            'id' => 5, 'name' => 'test'
        ]);
    }
}