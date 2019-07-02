<?php

declare(strict_types=1);

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

namespace Tenancy\Affects\URL;

use Illuminate\Contracts\Routing\UrlGenerator;
use Tenancy\Affects\Affect;
use Tenancy\Concerns\DispatchesEvents;

class ConfiguresURL extends Affect
{
    use DispatchesEvents;

    public function fire(): void
    {
        /** @var UrlGenerator $url */
        $url = resolve(UrlGenerator::class);

        if ($this->event->tenant) {
            $this->events()->dispatch(new Events\ConfigureURL($this->event, $url));
        }
    }
}
