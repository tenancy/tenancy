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

namespace Tenancy\Misc\Wingman\CLI\Traits;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait WorksWithSymfony
{
    /** @var \Symfony\Component\Console\Output\ConsoleOutput */
    protected $symfonyOutput;

    /** @var InputInterface */
    protected $symfonyInput;

    /**
     * {@inheritdoc}
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        $this->symfonyOutput = $output;
        $this->symfonyInput = $input;

        $reflectionMethod = new \ReflectionMethod(get_parent_class(get_parent_class($this)), 'run');
        $reflectionMethod->invoke($this, $input, $output);
    }
}
