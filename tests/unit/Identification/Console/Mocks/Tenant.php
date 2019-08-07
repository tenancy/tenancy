<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Laravel Tenancy & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Identification\Console\Mocks;

use Symfony\Component\Console\Input\InputInterface;
use Tenancy\Identification\Contracts\Tenant as Contract;
use Tenancy\Identification\Drivers\Console\Contracts\IdentifiesByConsole;

class Tenant extends \Tenancy\Testing\Mocks\Tenant implements IdentifiesByConsole
{
    /**
     * Specify whether the tenant model is matching the request.
     *
     * @param InputInterface $input
     *
     * @return null|Contract
     */
    public function tenantIdentificationByConsole(InputInterface $input): ?Contract
    {
        return $input->hasParameterOption('--tenant')
            ? $this->newQuery()
                ->where('name', $input->getParameterOption('--tenant'))
                ->first()
            : null;
    }
}
