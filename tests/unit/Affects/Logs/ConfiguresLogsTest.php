<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Laravel Tenancy & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Affects\Logs;

use Psr\Log\LoggerInterface;
use Tenancy\Affects\Logs\Events\ConfigureLogs;
use Tenancy\Affects\Logs\Provider;
use Tenancy\Facades\Tenancy;
use Tenancy\Testing\Mocks\Tenant;
use Tenancy\Testing\TestCase;

class ConfiguresLogsTest extends TestCase
{
    protected $additionalProviders = [Provider::class];

    /**
     * @var Tenant
     */
    protected $tenant;

    /**
     * @var string
     */
    protected $file;

    protected function afterSetUp()
    {
        $this->tenant = $this->mockTenant();
        $this->file = '/tmp/log-'.$this->tenant->getTenantKey();
        if (file_exists($this->file)) {
            unlink($this->file);
        }

        $this->events->listen(ConfigureLogs::class, function (ConfigureLogs $event) {
            $event->config['driver'] = 'single';
            $event->config['level'] = 'debug';
            $event->config['path'] = '/tmp/log-'.$event->event->tenant->getTenantKey();
        });
    }

    /**
     * @test
     */
    public function configuration_initially_empty()
    {
        if (file_exists($file = storage_path('/logs/laravel.log'))) {
            unlink($file);
        }
        /** @var LoggerInterface $logger ; emergency logger logs to laravel.log */
        $logger = logger()->driver('tenant');

        $logger->debug('no tenant');
        $this->assertFileExists($file);
        $this->assertFileNotExists($this->file);
    }

    /**
     * @test
     */
    public function switching_enabled_tenant_logger()
    {
        $this->resolveTenant($this->tenant);

        Tenancy::getTenant();

        /** @var LoggerInterface $logger */
        $logger = logger()->driver('tenant');

        $logger->debug($entry = "key: {$this->tenant->getTenantKey()}");

        $this->assertFileExists($this->file);
        $this->assertStringContainsString($entry, file_get_contents($this->file));
    }
}
