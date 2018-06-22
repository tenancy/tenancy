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

namespace Tenancy\Identification\Support;

use Illuminate\Support\Collection;

class TenantModelCollection extends Collection
{
    public function filterByContract(string $contract)
    {
        return $this->filter(function (string $item) use ($contract) {
            return ($contracts = class_implements($item)) && in_array($contracts, $contract);
        });
    }
}
