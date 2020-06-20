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

namespace Tenancy\SDK\Identification\Console\Traits;

use Symfony\Component\Console\Input\InputInterface;
use Tenancy\Identification\Contracts\Tenant;

trait IdentifyConsole
{
    /**
     * Identifies the tenant through the mode specified in the config.
     *
     * @param InputInterface $input
     *
     * @return Tenant|null
     */
    public function tenantIdentificationByConsole(InputInterface $input): ?Tenant
    {
        $function = 'consoleIdentify'.config('tenancy.sdk.identification.console.mode');

        return $this->{$function}($input);
    }

    /**
     * Always identifies as null.
     *
     * @param InputInterface $input
     *
     * @return null
     */
    protected function consoleIdentifyNone(InputInterface $input)
    {
        return null;
    }

    /**
     * Always identifies as the first of the query.
     *
     * @param InputInterface $input
     *
     * @return null
     */
    protected function consoleIdentifyFirst(InputInterface $input)
    {
        return $this->newQuery()->first();
    }

    /**
     * Dump dies the event.
     *
     * @param InputInterface $input
     *
     * @return void
     */
    protected function consoleIdentifyDie(InputInterface $input)
    {
        dd($input);
    }

    /**
     * Dumps the event and returns null.
     *
     * @param InputInterface $input
     *
     * @return null
     */
    protected function consoleIdentifyDump(InputInterface $input)
    {
        dump($input);

        return null;
    }

    /**
     * Determines the tenant based on the provided key.
     *
     * @param InputInterface $input
     *
     * @return Tenant|null
     */
    protected function consoleIdentifyKey(InputInterface $input)
    {
        return $this->newQuery()->where($this->getTenantKeyName(), $input->getParameterOption('--tenant'))->first();
    }

    /**
     * Determines the tenant based on the provided key.
     *
     * @param InputInterface $input
     *
     * @return Tenant|null
     */
    protected function consoleIdentifyCombination(InputInterface $input)
    {
        if ($this->getTenantIdentifier() != $input->getParameterOption('--tenant-identifier')) {
            return null;
        }

        return $this->consoleIdentifyKey($input);
    }
}
