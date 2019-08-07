<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Laravel Tenancy & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Database;

use Tenancy\Database\Contracts\ProvidesPassword;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;

class PasswordGeneratorTest extends TestCase
{
    /**
     * @var Tenant
     */
    protected $tenant;

    /**
     * @test
     */
    public function test_password_generator_output()
    {
        config(['tenancy.key' => 'YYTzkkfJaiSt+rwvCUq8rsdYWy5/fhasqjpHl8SyUJw=']);

        $this->tenant = $this->createMockTenant([
            'id' => 641641641,
        ]);

        $this->assertEquals('04a77b76d399fd33c99306cd3503a1e2', resolve(ProvidesPassword::class)->generate($this->tenant));
    }
}
