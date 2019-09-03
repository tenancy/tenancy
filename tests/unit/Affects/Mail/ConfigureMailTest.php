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
use Swift_Message;
use Tenancy\Affects\Mail\Events\ConfigureMail;
use Tenancy\Affects\Mail\Provider;
use Tenancy\Facades\Tenancy;
use Tenancy\Testing\TestCase;

class ConfigureMailTest extends TestCase
{
    protected $additionalProviders = [Provider::class];

    protected $tenant;

    public $emails = [];

    protected function afterSetUp()
    {
        $this->app->resolving('mailer', function ($Mailer) {
            $Mailer->getSwiftMailer()->registerPlugin(new SwiftTestPlugin($this));
        });

        $this->tenant = $this->mockTenant();
        $this->resolveTenant($this->tenant);
    }

    protected function getAddressFromMessage(Swift_Message $message){
        return array_keys($message->getFrom())[0];
    }

    /**
     * @test
     */
    public function can_set_config(){
        $this->events->listen(ConfigureMail::class, function (ConfigureMail $event) {
            $event->setFrom($event->event->tenant->email, $event->event->tenant->name);
        });

        Tenancy::getTenant();
        Mail::to('example@example.com')->send(new Mocks\Mail());
        $email = array_shift($this->emails);

        $this->assertEquals(
            $this->tenant->email,
            $this->getAddressFromMessage($email)
        );
    }

    /**
     * @test
     */
    public function succesful_switch_on_more_tenants()
    {
        $this->events->listen(ConfigureMail::class, function (ConfigureMail $event) {
            $event->setFrom($event->event->tenant->email);
        });

        $first = $this->createMockTenant();
        $second = $this->createMockTenant();

        $this->environment->setTenant($first);
        Mail::to('example@example.com')->send(new Mocks\Mail());
        $firstMail = array_shift($this->emails);

        $this->environment->setTenant($second);
        Mail::to('example@example.com')->send(new Mocks\Mail());
        $secondMail = array_shift($this->emails);

        $this->assertNotEquals(
            $this->getAddressFromMessage($firstMail),
            $this->getAddressFromMessage($secondMail),
            "Both emails have the same 'from' email, meaning the tenant did not switch properly"
        );

        $this->assertEquals(
            $first->email,
            $this->getAddressFromMessage($firstMail)
        );

        $this->assertEquals(
            $second->email,
            $this->getAddressFromMessage($secondMail)
        );
    }
}
