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

namespace Tenancy\Tests\Framework\Unit;

use InvalidArgumentException;
use Tenancy\Affects\AffectResolver;
use Tenancy\Affects\Contracts\ResolvesAffects;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Mocks\Affects;

class AffectResolverTest extends TestCase
{
    /** @test */
    public function by_default_the_affects_resolver_is_registered()
    {
        $this->assertInstanceOf(
            AffectResolver::class,
            $this->app->make(ResolvesAffects::class)
        );
    }

    /** @test */
    public function by_default_no_affects_are_registered()
    {
        $this->assertEmpty(
            $this->app->make(ResolvesAffects::class)->getAffects()
        );
    }

    /** @test */
    public function it_is_registered_as_singleton()
    {
        $resolver = $this->app->make(ResolvesAffects::class);
        $resolver->addAffect(Affects\ValidAffect::class);

        $this->assertEquals(
            $resolver,
            $this->app->make(ResolvesAffects::class)
        );

        $this->assertNotEmpty(
            $this->app->make(ResolvesAffects::class)->getAffects()
        );
    }

    /** @test */
    public function it_validates_affects()
    {
        $this->expectException(InvalidArgumentException::class);

        /** @var ResolvesAffects $resolver */
        $resolver = $this->app->make(ResolvesAffects::class);
        $resolver->addAffect(Affects\InvalidAffect::class);
    }

    /** @test */
    public function it_adds_affects()
    {
        /** @var ResolvesAffects $resolver */
        $resolver = $this->app->make(ResolvesAffects::class);
        $resolver->addAffect(Affects\ValidAffect::class);

        $this->assertContains(
            Affects\ValidAffect::class,
            $resolver->getAffects()
        );
    }

    /** @test */
    public function it_sets_affects()
    {
        /** @var ResolvesAffects $resolver */
        $resolver = resolve(ResolvesAffects::class);

        $resolver->setAffects([]);
        $this->assertEquals(
            [],
            $resolver->getAffects()
        );

        $resolver->setAffects([
            Affects\ValidAffect::class,
        ]);

        $this->assertEquals(
            [Affects\ValidAffect::class],
            $resolver->getAffects()
        );
    }
}
