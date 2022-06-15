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

namespace Tenancy\Affects\Mails;

use Illuminate\Mail\Mailer;
use Tenancy\Affects\Affect;
use Tenancy\Concerns\DispatchesEvents;

class ConfiguresMails extends Affect
{
    use DispatchesEvents;

    public function fire(): void
    {
        /** @var Mailer $mailer */
        $mailer = resolve(Mailer::class);

        $this->events()->dispatch(new Events\ConfigureMails($this->event, $mailer));
    }
}
