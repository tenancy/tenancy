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

namespace Tenancy\Tests\Affects\Mails\Unit;

use Illuminate\Mail\Transport\ArrayTransport;
use Mockery;
use Tenancy\Affects\Mails\Events\ConfigureMails;
use Tenancy\Affects\Mails\Provider;
use Tenancy\Facades\Tenancy;
use Tenancy\Tests\Affects\AffectsEventUnitTestCase;

class ConfigureMailsTest extends AffectsEventUnitTestCase
{
    protected $affectsProvider = Provider::class;

    protected $event = ConfigureMails::class;

    /** @test */
    public function the_event_can_replace_swiftmailer()
    {
        $this->app->register($this->affectsProvider);

        $this->events->listen($this->event, function (ConfigureMails $event) {
            $event->replaceSwiftMailer(new ArrayTransport());

            $this->assertInstanceOf(ArrayTransport::class, $event->mailer->getSwiftMailer()->getTransport());
        });

        Tenancy::setTenant($this->tenant);
    }

    /** @test */
    public function the_event_can_delegate_to_mailer()
    {
        $this->app->register($this->affectsProvider);

        $this->events->listen($this->event, function (ConfigureMails $e) {
            $mailerSpy = Mockery::spy(get_class($e->mailer));

            $e->mailer = $mailerSpy;

            $e->to('test@example.com');

            $mailerSpy->shouldHaveReceived()->to('test@example.com');
        });

        Tenancy::setTenant($this->tenant);
    }
}
