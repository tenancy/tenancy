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
use Illuminate\Support\Facades\Mail;
use Mockery;
use Tenancy\Affects\Mails\Events\ConfigureMails;
use Tenancy\Affects\Mails\Provider;
use Tenancy\Facades\Tenancy;
use Tenancy\Tests\Affects\AffectsEventUnitTestCase;
use Tenancy\Tests\Affects\Mails\TestMail;

class ConfigureMailsTest extends AffectsEventUnitTestCase
{
    protected $affectsProvider = Provider::class;

    protected $event = ConfigureMails::class;

    /** @test */
    public function the_event_can_replace_symfony_transport()
    {
        $this->app->register($this->affectsProvider);

        $this->events->listen($this->event, function (ConfigureMails $event) {
            $event->replaceSymfonyTransport(new ArrayTransport());

            $this->assertInstanceOf(ArrayTransport::class, $event->mailer->getSymfonyTransport());
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

    /** @test */
    public function mailer_can_be_faked()
    {
        Mail::fake();

        Mail::to('test@example.com')->send(new TestMail());

        Mail::assertSent(TestMail::class, function (TestMail $mail) {
            return $mail->hasTo('test@example.com');
        });

        Tenancy::setTenant($this->tenant);
    }
}
