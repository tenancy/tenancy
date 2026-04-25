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
use Symfony\Component\Mailer\Transport\TransportInterface;
use Tenancy\Identification\Events\Switched;

class ConfigureMails
{
    public function __construct(
        public Switched $event,
        public Mailer $mailer
    ) {
    }

    public function replaceSymfonyTransport(TransportInterface $transport): static
    {
        $this->mailer->setSymfonyTransport($transport);

        return $this;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->mailer, $name], $arguments);
    }
}
