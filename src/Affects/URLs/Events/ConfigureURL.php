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

namespace Tenancy\Affects\URLs\Events;

use Illuminate\Contracts\Routing\UrlGenerator;
use Tenancy\Identification\Events\Switched;

class ConfigureURL
{
    /**
     * @var Switched
     */
    public $event;

    /**
     * @var UrlGenerator
     */
    public $url;

    public function __construct(Switched $event, UrlGenerator $url)
    {
        $this->event = $event;
        $this->url = $url;
    }

    public function changeRoot(string $url)
    {
        return $this->url->forceRootUrl($url);
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->url, $name], $arguments);
    }
}
