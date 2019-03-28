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

namespace Tenancy\Tests\Identification\Drivers\Queue;

use ReflectionException;
use Tenancy\Testing\TestCase;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Identification\Drivers\Queue\Providers\IdentificationProvider;

class IdentifyInQueueTest extends TestCase
{
    protected $additionalProviders = [IdentificationProvider::class];

    /**
     * @test
     */
    public function queue_identifies_tenant()
    {
        $tenant = $this->mockTenant();

        $this->environment->setTenant($tenant);

        $this->expectException(ReflectionException::class);

        dispatch(new Mocks\Job);
    }
}
