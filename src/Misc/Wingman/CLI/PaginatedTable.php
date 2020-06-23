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

namespace Tenancy\Misc\Wingman\CLI;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\ForwardsCalls;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class PaginatedTable
{
    use ForwardsCalls;

    /** @var int */
    private $page = 1;

    /** @var int */
    private $perPage = 10;

    /** @var \Illuminate\Database\Eloquent\Builder */
    private $query;

    /** @var OutputInterface */
    private $output;

    /** @var Table */
    private $table;

    /** @var string|null */
    private $title;

    /**
     * Constructs a new instance.
     *
     * @param Builder         $query
     * @param OutputInterface $output
     */
    public function __construct(Builder $query, OutputInterface $output)
    {
        $this->query = $query;
        $this->output = $output;
        $this->table = new Table($this->output);

        $this->table->setHeaderTitle(Str::pluralStudly(class_basename($this->query->first())));
        $this->updateTable();
    }

    /**
     * Set the base query that should be used.
     * CAUTION: Resets table.
     *
     * @param Builder $query
     *
     * @return self
     */
    public function setQuery(Builder $query)
    {
        $this->query = $query;
        $this->page = 1;

        return $this;
    }

    /**
     * Sets the title for the table.
     *
     * @param string $title
     *
     * @return self
     */
    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Changes the page to a specific number.
     *
     * @param int $page
     *
     * @return self
     */
    public function changePage(int $page)
    {
        $this->page = $page;
        $this->updateTable();

        return $this;
    }

    /**
     * Goes to the next page.
     *
     * @return self
     */
    public function nextPage()
    {
        $this->page++;
        $this->updateTable();

        return $this;
    }

    /**
     * Goes back to the previos page.
     *
     * @return self
     */
    public function previousPage()
    {
        $this->page--;
        $this->updateTable();

        return $this;
    }

    /**
     * Changes the amount that should be shown per page.
     *
     * @param int $perPage
     *
     * @return self
     */
    public function perPage(int $perPage)
    {
        $this->perPage = $perPage;
        $this->updateTable();

        return $this;
    }

    /**
     * Updates the output.
     *
     * @return void
     */
    private function updateTable()
    {
        $this->table->setHeaders(
            array_keys($this->formatModel($this->query->first()))
        );
        $clone = clone $this->query;
        $this->table->setRows(
            $clone->forPage($this->page, $this->perPage)
                ->get()
                ->map(function (Model $model) {
                    return $this->formatModel($model);
                })
                ->toArray()
        );
        $this->table->setFooterTitle('Page '.$this->page.'/'.$this->query->count());
    }

    /**
     * Renders the table.
     *
     * @return self
     */
    public function render()
    {
        if (method_exists($this->output, 'clear')) {
            $this->output->clear();
        }

        $this->table->render();

        return $this;
    }

    /**
     * Formats the model to an array.
     *
     * @param Model $model
     *
     * @return array
     */
    protected function formatModel(Model $model): array
    {
        return $model->toArray();
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->table, $method, $parameters);
    }
}
