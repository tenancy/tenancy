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

 namespace Tenancy\Tests\Lifecycle;

use InvalidArgumentException;
use Tenancy\Testing\TestCase;
use Tenancy\Lifecycle\Contracts\ResolvesHooks;
use Tenancy\Tests\Lifecycle\Mocks\InvalidHook;

class HookResolverTest extends TestCase{

    /**
     * @test
     */
    public function validates_hooks()
    {
        $this->expectException(InvalidArgumentException::class);

        $resolver = resolve(ResolvesHooks::class);
        $resolver->addHook(InvalidHook::class);
    }
}
