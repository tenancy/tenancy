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

namespace Tenancy\Affects\Mail\Events;

use Illuminate\Contracts\Mail\Mailer;
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
     * Set the basic from address for the mailer
     *
     * @param string $address
     * @param string|null $name
     * @return void
     */
    public function setFrom(string $address, string $name = null){
        $this->mailer->alwaysFrom($address, $name);
    }
}
