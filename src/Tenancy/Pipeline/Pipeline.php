<?php declare(strict_types=1);

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

namespace Tenancy\Pipeline;

use Tenancy\Pipeline\Contracts\Step;
use Tenancy\Pipeline\Events\Fired;

class Pipeline
{
    protected $steps;

    public function __construct(Steps $steps = null)
    {
        $this->steps = $steps ?? new Steps();
    }

    public function getSteps(): Steps
    {
        return $this->steps;
    }

    /**
     * @param array|Step[] $steps
     * @return Pipeline
     */
    public function setSteps(array $steps): Pipeline
    {
        $this->steps = new Steps($steps);

        return $this;
    }

    public function handle($event, callable $fire = null)
    {
        $steps = $this->steps;

        event((new Events\Processing($event, $this))->steps($steps));

        $steps = $steps
            ->resolve($event, $this)
            ->prioritized()
            ->fires();

        event((new Events\Resolved($event, $this))->steps($steps));

        if ($fire) {
            $fire($steps);
        } else {
            $steps->each(function (Step $step) {
                $step->fire();
            });
        }

        event(new Events\Fired($event, $this));

        return $steps;
    }


    public function __call($name, $arguments)
    {
        return $this->steps->{$name}($arguments);
    }
}
