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

namespace Tenancy\Affects\Mails\Events;

use Illuminate\Contracts\Mail\Mailer;
use Swift_Mailer;
use Swift_Transport;
use Tenancy\Identification\Events\Switched;

class ConfigureMails
{
    /**
     * @var Switched
     */
    public $event;

    /**
     * @var Mailer
     */
    public $mailer;

    public function __construct(Switched $event, Mailer $mailer)
    {
        $this->event = $event;
        $this->mailer = $mailer;
    }

    /**
     * Replace the Swift Mailer with a new one.
     *
     * @param Swift_Transport $transport
     *
     * @return self
     */
    public function replaceSwiftMailer(Swift_Transport $transport)
    {
        $this->mailer->setSwiftMailer(new Swift_Mailer($transport));

        return $this;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->mailer, $name], $arguments);
    }
}
