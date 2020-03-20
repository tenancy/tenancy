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

namespace Tenancy\Tests\Mocks;

use Illuminate\Database\ConnectionResolverInterface;

class ConnectionResolver implements ConnectionResolverInterface
{
    /**
     * @var string
     */
    private $connection;

    /**
     * @var ConnectionResolverInterface
     */
    private $db;

    public function __construct(string $connection, ConnectionResolverInterface $db)
    {
        $this->connection = $connection;
        $this->db = $db;
    }

    /**
     * Get a database connection instance.
     *
     * @param string $name
     *
     * @return \Illuminate\Database\ConnectionInterface
     */
    public function connection($name = null)
    {
        return $this->db->connection($name ?? $this->getDefaultConnection());
    }

    /**
     * Get the default connection name.
     *
     * @return string
     */
    public function getDefaultConnection()
    {
        return $this->connection;
    }

    /**
     * Set the default connection name.
     *
     * @param string $name
     *
     * @return void
     */
    public function setDefaultConnection($name)
    {
        $this->connection = $name;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->db, $name], $arguments);
    }
}
