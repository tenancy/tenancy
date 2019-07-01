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

use Illuminate\Support\Collection;
use Tenancy\Pipeline\Contracts\Step;

class Steps extends Collection
{

    /**
     * @param Step|int $previous ; priority or Step to append Step after.
     * @param Step $step
     */
    public function after($previous, Step $step)
    {
    }
}
