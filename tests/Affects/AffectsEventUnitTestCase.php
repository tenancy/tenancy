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

namespace Tenancy\Tests\Affects;

use Illuminate\Support\Facades\Event;
use Tenancy\Facades\Tenancy;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Identification\Events\Switched;
use Tenancy\Testing\TestCase;

abstract class AffectsEventUnitTestCase extends TestCase
{
    /** @var Tenant */
    protected $tenant;

    /** @var string */
    protected $event;

    /** @var string */
    protected $affectsProvider;

    /** @var array */
    protected $eventContains = [];

    /** @var array */
    private $defaultContent = [
        'event' => Switched::class,
    ];

    protected function afterSetUp()
    {
        $this->tenant = $this->mockTenant();
    }

    /**
     * Gets the combined contents the event should have.
     *
     * @return array
     */
    private function getContents()
    {
        return array_merge($this->defaultContent, $this->eventContains);
    }

    /** @test */
    public function the_event_is_not_triggered_without_provider()
    {
        Event::fake($this->event);

        Tenancy::setTenant($this->tenant);

        Event::assertNotDispatched($this->event);
    }

    /** @test */
    public function the_event_is_triggered()
    {
        $this->app->register($this->affectsProvider);

        Event::fake($this->event);

        Tenancy::setTenant($this->tenant);

        Event::assertDispatched($this->event);
    }

    /** @test */
    public function the_event_contains_the_right_data()
    {
        $this->app->register($this->affectsProvider);

        $this->events->listen($this->event, function ($event) {
            foreach ($this->getContents() as $key => $instance) {
                $this->assertInstanceOf($instance, $event->{$key});
            }
        });

        Tenancy::setTenant($this->tenant);
    }
}
