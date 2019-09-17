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

namespace Tenancy\Affects\Mails\Events;

use GuzzleHttp\Client;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Mail\Transport\MailgunTransport;
use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_Transport;
use Tenancy\Identification\Events\Switched;

class ConfigureMail
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
     * Set the basic from address for the mailer.
     *
     * @param string      $address
     * @param string|null $name
     *
     * @return void
     */
    public function setFrom(string $address, string $name = null)
    {
        $this->mailer->alwaysFrom($address, $name);

        return $this;
    }

    /**
     * Set the basic from address for the mailer.
     *
     * @param string      $address
     * @param string|null $name
     *
     * @return void
     */
    public function loadMailgunConfig(string $key, string $domain, string $endpoint = null)
    {
        $this->replaceSwiftMailer(new MailgunTransport(new Client(config('services.mailgun')), $key, $domain, $endpoint));

        return $this;
    }

    /**
     * Set the basic from address for the mailer.
     *
     * @param string      $address
     * @param string|null $name
     *
     * @return void
     */
    public function loadSmtpConfig(string $host, int $port, string $username = null, string $password = null, string $encryption = 'tls')
    {
        if (!($transport = $this->mailer->getSwiftMailer()->getTransport()) instanceof Swift_SmtpTransport) {
            $transport = new Swift_SmtpTransport($host, $port, $encryption);
        }

        if ($username !== null) {
            $transport->setUsername($username);
            $transport->setPassword($password);
        }

        return $this->replaceSwiftMailer($transport);
    }

    /**
     * Set the basic from address for the mailer.
     *
     * @param string      $address
     * @param string|null $name
     *
     * @return void
     */
    public function replaceSwiftMailer(Swift_Transport $transport)
    {
        $this->mailer->setSwiftMailer(new Swift_Mailer($transport));

        return $this;
    }
}
