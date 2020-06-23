<?php

namespace Tenancy\Misc\Wingman\CLI\Traits;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

trait UsesDirectInteractions
{
    /**
     * Uses the Symfony Question Helper in order to ask a question.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param string $question
     * @param mixed $default
     *
     * @return mixed
     */
    protected function promptQuestion(InputInterface $input, OutputInterface $output, string $question, $default = null)
    {
        $helper = $this->getHelper('question');

        $question = new Question($question, $default);

        return $helper->ask(
            $input,
            $output,
            $question
        );
    }

    /**
     * Prompts the user with a choice
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param string $question
     * @param array $choices
     * @param mixed $default
     *
     * @return mixed
     */
    protected function promptChoice(InputInterface $input, OutputInterface $output, string $question, array $choices, $default = null)
    {
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            $question,
            $choices,
            $default
        );

        return $helper->ask(
            $input,
            $output,
            $question
        );
    }
}
