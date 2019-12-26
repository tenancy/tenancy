<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Framework\Affects;

use InvalidArgumentException;
use Tenancy\Affects\Contracts\ResolvesAffects;
use Tenancy\Testing\TestCase;

class AffectsResolverTest extends TestCase
{
    /**
     * @test
     */
    public function validates_affects()
    {
        $this->expectException(InvalidArgumentException::class);

        /** @var ResolvesAffects $resolver */
        $resolver = resolve(ResolvesAffects::class);
        $resolver->addAffect(Mocks\InvalidAffects::class);
    }

    /**
     * @test
     */
    public function sets_affects()
    {
        /** @var ResolvesAffects $resolver */
        $resolver = resolve(ResolvesAffects::class);

        $resolver->setAffects([]);
        $this->assertEquals(
            [],
            $resolver->getAffects()
        );

        $resolver->setAffects([
            Mocks\ValidAffects::class,
        ]);

        $this->assertEquals(
            [Mocks\ValidAffects::class],
            $resolver->getAffects()
        );
    }
}
