<?php declare(strict_types=1);

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

namespace Tenancy\Database\Drivers\Mysql\Driver;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Tenancy\Database\Contracts\ProvidesDatabase;
use Tenancy\Database\Drivers\Mysql\Concerns\ManagesSystemConnection;
use Tenancy\Database\Events\Drivers\Configuring;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Database\Contracts\ProvidesPassword;

class Mysql implements ProvidesDatabase
{
    public function configure(Tenant $tenant): array
    {
        if ($name = config('tenancy.db-driver-mysql.use-connection')) {
            return config("tenancy.database.connections.$name");
        }

        $config = config('tenancy.db-driver-mysql.preset', []);

        if ($tenant->isDirty($tenant->getTenantKeyName())) {
            $config['oldUsername'] = $tenant->getOriginal($tenant->getTenantKeyName());
        }

        $config['database'] = $config['username'] = $tenant->getTenantKey();
        $config['password'] = resolve(ProvidesPassword::class)->generate($tenant);

        event(new Configuring($tenant, $config, $this));

        return $config;
    }

    public function create(Tenant $tenant): bool
    {
        $config = $this->configure($tenant);

        return $this->process($tenant, [
            'user' => "CREATE USER IF NOT EXISTS `{$config['username']}`@'{$config['host']}' IDENTIFIED BY '{$config['password']}'",
            'database' => "CREATE DATABASE `{$config['database']}`",
            'grant' => "GRANT ALL ON `{$config['database']}`.* TO `{$config['username']}`@'{$config['host']}'"
        ]);
    }

    public function update(Tenant $tenant): bool
    {
        $config = $this->configure($tenant);

        if (!isset($config['oldUsername'])) {
            return false;
        }
        return $this->process($tenant, [
            'user' => "RENAME USER `{$config['oldUsername']}`@'{$config['host']}' TO `{$config['username']}`@'{$config['host']}'",
        ]);
    }

    public function delete(Tenant $tenant): bool
    {
        $config = $this->configure($tenant);

        return $this->process($tenant, [
            'user' => "DROP USER `{$config['username']}`@'{$config['host']}'",
            'database' => "DROP DATABASE IF EXISTS `{$config['database']}`"
        ]);
    }

    protected function system(Tenant $tenant): ConnectionInterface
    {
        $connection = config('tenancy.db-driver-mysql.system-connection');

        if (in_array(ManagesSystemConnection::class, class_implements($tenant))) {
            $connection = $tenant->getManagingSystemConnection() ?? $connection;
        }

        return DB::connection($connection);
    }

    protected function process(Tenant $tenant, array $statements): bool
    {
        $success = false;

        $this->system($tenant)->beginTransaction();

        foreach ($statements as $statement) {
            try {
                $success = $this->system($tenant)->statement($statement);

                if (! $success) {
                    throw new QueryException($statement);
                }
            } catch (QueryException $e) {
                report($e);

                $this->system($tenant)->rollBack();
            }
        }

        $this->system($tenant)->commit();

        return $success;
    }
}
