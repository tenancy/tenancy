<?php

namespace Tenancy\Affects\Models\Database;

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
     * @return \Illuminate\Database\ConnectionInterface
     */
    public function connection($name = null)
    {
        return $this->db->connection($name);
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
