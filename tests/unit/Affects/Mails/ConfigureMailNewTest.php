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

namespace Tenancy\Tests\Affects\Mails;

use Illuminate\Support\Facades\Mail;
use Swift_Message;
use Tenancy\Affects\Mails\Events\ConfigureMail;
use Tenancy\Affects\Mails\Provider;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Tests\Affects\AffectsTestCase;

class ConfigureMailNewTest extends AffectsTestCase
{
    /**
     * @var bool
     */
    protected $forwardCallTest = false;

    /**
     * @var bool
     */
    protected $undoTest = false;

    protected $additionalProviders = [Provider::class];

    public $email;

    protected function afterSetUp()
    {
        $this->app->resolving('mailer', function ($Mailer) {
            $Mailer->getSwiftMailer()->registerPlugin(new SwiftTestPlugin($this));
        });

        parent::afterSetUp();
    }

    protected function getAddressFromMessage(Swift_Message $message = null)
    {
        if (empty($message)) {
            return '';
        }

        return array_keys($message->getFrom())[0];
    }

    protected function registerAffecting()
    {
        $this->events->listen(ConfigureMail::class, function (ConfigureMail $event) {
            $event->alwaysFrom($event->event->tenant->email, $event->event->tenant->name);
        });
    }

    protected function assertAffected(Tenant $tenant)
    {
        $this->assertEquals(
            $tenant->email,
            $this->getAddressFromMessage($this->email)
        );
    }

    protected function assertNotAffected(Tenant $tenant)
    {
        $this->assertNotEquals(
            $tenant->email,
            $this->getAddressFromMessage($this->email)
        );
    }

    protected function afterIdentification(Tenant $tenant = null)
    {
        Mail::to('example@example.com')->send(new Mocks\Mail());
    }

    protected function registerForwardingCall()
    {
        //
    }
}
