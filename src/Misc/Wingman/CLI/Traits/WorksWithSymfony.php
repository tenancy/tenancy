<?php

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
     * {@inheritDoc}
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        $this->symfonyOutput = $output;
        $this->symfonyInput = $input;

        $reflectionMethod = new \ReflectionMethod(get_parent_class(get_parent_class($this)), 'run');
        $reflectionMethod->invoke($this, $input, $output);
    }
}
