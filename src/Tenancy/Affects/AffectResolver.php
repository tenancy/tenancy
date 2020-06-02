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

namespace Tenancy\Affects;

use InvalidArgumentException;
use Tenancy\Affects\Contracts\ResolvesAffects;
use Tenancy\Contracts\AffectsApp;
use Tenancy\Pipeline\Pipeline;

class AffectResolver extends Pipeline implements ResolvesAffects
{
    public function addAffect($affect)
    {
        if (!in_array(AffectsApp::class, class_implements($affect))) {
            throw new InvalidArgumentException("$affect has to implement ".AffectsApp::class);
        }

        $this->steps->add($affect);

        return $this;
    }

    public function getAffects(): array
    {
        return $this->getSteps()->toArray();
    }

    public function setAffects(array $affects)
    {
        $this->setSteps($affects);

        return $this;
    }
}
