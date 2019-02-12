<?php

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

use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\Facades\Event;
use Tenancy\Identification\Drivers\Queue\Providers\IdentificationProvider;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;

class IdentifyInQueueTest extends TestCase
{
    protected $additionalProviders = [IdentificationProvider::class];

    /**
     * @test
     */
    public function queue_identifies_tenant()
    {
        $tenant = factory(Tenant::class)->make();

        $this->environment->setTenant($tenant);

        Event::listen(JobProcessed::class, function ($event) use ($tenant) {
            $payload = json_decode($event->job->getRawBody(), true);
            $this->assertEquals(get_class($tenant), $payload['tenant_class']);
        });

        dispatch(new Mocks\Job);
    }
}
