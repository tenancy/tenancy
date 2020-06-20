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
