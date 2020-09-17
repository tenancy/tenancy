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

namespace Tenancy\Hooks\Database\Support;

use Closure;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Traits\Macroable;

class QueryManager
{
    use Macroable;

    /** @var ConnectionInterface */
    protected $connection;

    /** @var bool */
    protected $status = true;

    public function setConnection(ConnectionInterface $connection): self
    {
        $this->connection = $connection;

        return $this;
    }

    public function process(Closure $closure): self
    {
        $closure->call($this);

        return $this;
    }

    public function processTransaction(Closure $closure): self
    {
        $this->connection->beginTransaction();

        try {
            $closure->call($this);
            // @codeCoverageIgnoreStart
        } catch (QueryException $e) {
            $this->connection->rollBack();

            throw $e;
            // @codeCoverageIgnoreEnd
        }

        $this->connection->commit();

        return $this;
    }

    protected function statement($sql)
    {
        $this->status = $this->status && $this->connection->statement($sql);
    }

    public function getStatus(): bool
    {
        return $this->status;
    }
}
