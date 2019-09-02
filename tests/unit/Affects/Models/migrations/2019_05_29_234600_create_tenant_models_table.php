<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantModelsTable extends Migration
{
    public function up()
    {
        Schema::create('tenant_models', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tenant_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('tenant_models');
    }
}
