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

namespace Tenancy\Tests\Misc\Help\Feature;

use Tenancy\Misc\Help\Data\Packages\Framework;
use Tenancy\Misc\Help\PackageResolver;
use Tenancy\Testing\TestCase;

class PackageResolverTest extends TestCase
{
    /** @test */
    public function it_can_be_created_with_packages()
    {
        $resolver = new PackageResolver(['ExamplePackage']);

        $this->assertContains(
            'ExamplePackage',
            $resolver->getPackages()
        );
    }

    /** @test */
    public function it_can_register_packages()
    {
        $resolver = new PackageResolver();

        $this->assertEmpty($resolver->getPackages());

        $resolver->registerPackage(new Framework());

        $this->assertNotEmpty($resolver->getPackages());
    }
}
