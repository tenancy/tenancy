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

namespace Tenancy\Affects\Mails;

use Illuminate\Contracts\Mail\Mailer;
use Tenancy\Affects\Affect;
use Tenancy\Concerns\DispatchesEvents;

class ConfiguresMail extends Affect
{
    use DispatchesEvents;

    public function fire(): void
    {
        /** @var Mailer $mailer */
        $mailer = resolve(Mailer::class);

        if ($this->event->tenant) {
            $this->events()->dispatch(new Events\ConfigureMail($this->event, $mailer));
        }
    }
}
