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

namespace Tenancy\Misc\Wingman\CLI\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Misc\Wingman\CLI\Interactions\Next;
use Tenancy\Misc\Wingman\CLI\Interactions\Page;
use Tenancy\Misc\Wingman\CLI\Interactions\Previous;
use Tenancy\Misc\Wingman\CLI\Interactions\Quit;
use Tenancy\Misc\Wingman\CLI\PaginatedTable;
use Tenancy\Misc\Wingman\CLI\Traits\UsesDirectInteractions;
use Tenancy\Misc\Wingman\CLI\Traits\UsesInteractions;
use Tenancy\Misc\Wingman\CLI\Traits\UsesSections;
use Tenancy\Misc\Wingman\CLI\Traits\WorksWithSymfony;

class ListCommand extends Command
{
    use WorksWithSymfony;
    use UsesSections;
    use UsesInteractions;
    use UsesDirectInteractions;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wingman:list
                            {--chunk=10 : Chunks the tenants with the specific amount}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows the tenants';

    /** @var ResolvesTenants */
    private $resolver;

    /** @var PaginatedTable */
    private $table;

    /**
     * Constructs a new instance.
     *
     * @param ResolvesTenants $resolver
     */
    public function __construct(ResolvesTenants $resolver)
    {
        parent::__construct();
        $this->resolver = $resolver;
        $this->setInteractions([new Quit(), new Next(), new Previous(), new Page()]);
    }

    /**
     * Handles the function.
     *
     * @return void
     */
    public function handle()
    {
        $query = $this->getTenantQuery($this->resolver);

        $tableSection = $this->createSection();
        $interactionSection = $this->createSection();

        $this->table = new PaginatedTable($query, $tableSection);

        while (!(new Quit())->shouldReact($this->getInteraction())) {
            $interactionSection->clear();

            $this->table->render();

            $this->triggerInteraction($this->promptInteraction($interactionSection));
        }
    }

    /**
     * Prompts the user for an interaction.
     *
     * @param OutputInterface $output
     *
     * @return string
     */
    private function promptInteraction(ConsoleSectionOutput $output)
    {
        $this->setInteraction(
            $this->promptQuestion(
                $this->symfonyInput,
                $output,
                'What action would you like to perform?'
            )
        );

        return $this->getInteraction();
    }

    /**
     * Transforms a class string into an instance.
     *
     * @param string $class
     *
     * @return object
     */
    private function classToInstance(string $class)
    {
        return new $class();
    }

    /**
     * Sets up the tenant query.
     *
     * @param ResolvesTenants $resolver
     *
     * @return Builder
     */
    private function getTenantQuery(ResolvesTenants $resolver)
    {
        $tenantClasses = $resolver->getModels();

        if ($tenantClasses->isEmpty()) {
            $this->error('No Tenants registered');

            return;
        }

        $tenantClass = $tenantClasses->first();

        if ($tenantClasses->count() > 1) {
            $tenantClass = $this->promptChoice(
                $this->symfonyInput,
                $this->symfonyOutput,
                'What class would you like to use?',
                $tenantClasses->toArray()
            );
        }

        return $this->classToInstance($tenantClass)->newQuery();
    }

    /**
     * Forces the table to the next page.
     *
     * @param string $interaction
     *
     * @return void
     */
    protected function interactNext(string $interaction)
    {
        $this->table->nextPage();
    }

    /**
     * Forces the table to the previous page.
     *
     * @param string $interaction
     *
     * @return void
     */
    protected function interactPrevious(string $interaction)
    {
        $this->table->previousPage();
    }

    /**
     * Forces the table to a specific page.
     *
     * @param string $interaction
     *
     * @return void
     */
    protected function interactPage(string $interaction)
    {
        $page = intval($interaction);

        $this->table->changePage($page);
    }
}
