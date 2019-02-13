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

namespace Tenancy\Identification\Support;

use Illuminate\Support\Collection;

class TenantModelCollection extends Collection
{
    /**
     * @param string|array $contracts
     * @return static
     */
    public function filterByContract($contracts)
    {
        $contracts = array_wrap($contracts);

        return $this->filter(function (string $item) use ($contracts) {
            $implements = class_implements($item);

            return count(array_intersect($implements, $contracts));
        });
    }
}
