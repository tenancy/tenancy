<?php

namespace Tenancy\Tests;

trait UsesConnections
{
    /**
     * Gets the path to the sqlite database configuration
     *
     * @return string
     */
    protected function getSqliteConfigurationPath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Mocks'
            . DIRECTORY_SEPARATOR . 'Connections'
            . DIRECTORY_SEPARATOR . 'sqlite.php';
    }


    /**
     * Gets the path to the mysql database configuration
     *
     * @return string
     */
    protected function getMysqlConfigurationPath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Mocks'
            . DIRECTORY_SEPARATOR . 'Connections'
            . DIRECTORY_SEPARATOR . 'mysql.php';
    }
}
