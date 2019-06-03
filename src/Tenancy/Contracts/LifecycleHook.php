<?php

/*
 * This file is part of the tenancy/tenancy package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Contracts;

use Tenancy\Tenant\Events\Event;

interface LifecycleHook
{
    public function for(Event $event);

    public function fires(): bool;

    public function queued(): bool;

    public function priority(): int;

    public function fire(): void;

    public function queue(): ?string;
}
