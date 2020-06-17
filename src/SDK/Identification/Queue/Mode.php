<?php

namespace Tenancy\SDK\Identification\Queue;

use Tenancy\SDK\Identification\BaseMode;

class Mode extends BaseMode
{
    /** @var string */
    const KEY = 'key';

    /** @var string */
    const COMBINATION = 'combination';

    /** @var string */
    const MODEL = 'model';

    /** @var string */
    const PREFERMODEL = 'preferModel';
}
