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

namespace Tenancy\Affects\URL\Events;

use Tenancy\Identification\Events\Switched;
use Illuminate\Contracts\Routing\UrlGenerator;

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

    public function changeUrlTo(string $url){
        return $this->url->forceRootUrl($url);
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->url, $name], $arguments);
    }
}
