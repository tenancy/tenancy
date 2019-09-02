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

namespace Tenancy\Tests\Affects\Mail;

use Illuminate\Support\Facades\Mail;
use Tenancy\Affects\Mail\Events\ConfigureMail;
use Tenancy\Affects\Mail\Provider;
use Tenancy\Testing\TestCase;

class ConfigureMailTest extends TestCase
{
    protected $additionalProviders = [Provider::class];

    protected $tenant;

    public $emails = [];

    protected function afterSetUp()
    {
        $this->app->resolving('mailer', function ($Mailer){
            $Mailer->getSwiftMailer()->registerPlugin(New TestPlugin($this));
        });
    }

    /**
     * @test
     */
    public function adjusting_config_adjusts_from_email()
    {
        $this->events->listen(ConfigureMail::class, function (ConfigureMail $event){
            $event->setFrom($event->event->tenant->email);
        });

        $first = $this->createMockTenant();
        $second = $this->createMockTenant();

        $this->environment->setTenant($first);
        Mail::to('example@example.com')->send(new Mocks\Mail());
        $firstMail = $this->emails[0];
        $this->emails = [];

        $this->environment->setTenant($second);

        Mail::to('example@example.com')->send(new Mocks\Mail());
        $secondMail = $this->emails[0];

        $this->assertNotEquals(
            array_keys($firstMail->getFrom())[0],
            array_keys($secondMail->getFrom())[0],
            "Both emails have the same 'from' email, meaning the tenant did not switch properly"
        );

        $this->assertEquals(
            $first->email,
            array_keys($firstMail->getFrom())[0]
        );

        $this->assertEquals(
            $second->email,
            array_keys($secondMail->getFrom())[0]
        );
    }
}

use Swift_Events_SendListener;
use Swift_Events_SendEvent;

class TestPlugin implements Swift_Events_SendListener{

    protected $test;


    public function __construct($test)
    {
        $this->test = $test;
    }

    /**
     * Invoked immediately before the Message is sent.
     *
     * @param Swift_Events_SendEvent $evt
     */
    public function beforeSendPerformed(Swift_Events_SendEvent $evt){
        $this->test->emails[] = $evt->getMessage();
    }

    /**
     * Invoked immediately after the Message is sent.
     *
     * @param Swift_Events_SendEvent $evt
     */
    public function sendPerformed(Swift_Events_SendEvent $evt){
        $this->test->emails[] = $evt->getMessage();
    }
}
