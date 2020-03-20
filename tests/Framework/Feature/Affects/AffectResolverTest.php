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

namespace Tenancy\Tests\Framework\Feature\Affects;

use InvalidArgumentException;
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
    public function it_validates_affects()
    {
        $this->expectException(InvalidArgumentException::class);

        /** @var ResolvesAffects $resolver */
        $resolver = $this->resolver;
        $resolver->addAffect(Affects\InvalidAffect::class);
    }

    /** @test */
    public function it_adds_affects()
    {
        /** @var ResolvesAffects $resolver */
        $resolver = $this->resolver;
        $resolver->addAffect(Affects\ValidAffect::class);

        $this->assertContains(
            Affects\ValidAffect::class,
            $resolver->getAffects()
        );
    }

    /** @test */
    public function it_sets_affects()
    {
        $this->resolver->setAffects([]);
        $this->assertEquals(
            [],
            $this->resolver->getAffects()
        );

        $this->resolver->setAffects([
            Affects\ValidAffect::class,
        ]);

        $this->assertEquals(
            [Affects\ValidAffect::class],
            $this->resolver->getAffects()
        );
    }
}
