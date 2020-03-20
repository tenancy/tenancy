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

namespace Tenancy\Tests\Framework\Unit\Affects;

use Tenancy\Affects\AffectResolver;
use Tenancy\Affects\Contracts\ResolvesAffects;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Mocks\Affects;

class AffectResolverTest extends TestCase
{
    /** @var ResolvesAffects */
    private $resolver;

    protected function afterSetUp()
    {
        $this->resolver = $this->app->make(ResolvesAffects::class);
    }

    /** @test */
    public function by_default_the_affects_resolver_is_registered()
    {
        $this->assertInstanceOf(
            AffectResolver::class,
            $this->resolver
        );
    }

    /** @test */
    public function by_default_no_affects_are_registered()
    {
        $this->assertEmpty(
            $this->resolver->getAffects()
        );
    }

    /** @test */
    public function it_is_registered_as_singleton()
    {
        $resolver = $this->resolver;
        $resolver->addAffect(Affects\ValidAffect::class);

        $this->assertEquals(
            $resolver,
            $this->resolver
        );

        $this->assertNotEmpty(
            $this->resolver->getAffects()
        );
    }
}
