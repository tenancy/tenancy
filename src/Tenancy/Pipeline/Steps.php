<?php

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
