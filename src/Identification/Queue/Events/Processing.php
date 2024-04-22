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

namespace Tenancy\Identification\Drivers\Queue\Events;

use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\SerializesAndRestoresModelIdentifiers;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Identification\Drivers\Queue\Jobs\Job as TenancyJob;

class Processing
{
    use SerializesAndRestoresModelIdentifiers;

    public ?Tenant $tenant;

    public int|string|null $tenant_key;

    public ?string $tenant_identifier;

    public Job|TenancyJob $job;

    public function __construct(
        public JobProcessing $event
    ) {
        $payload = $event->job->payload();

        $command = Arr::get($payload, 'data.command');

        /** @var TenancyJob|Job $job */
        $job = $this->unserializeToJob($command);

        $this->tenant_key = $job?->getTenantKey() ?? $payload['tenant_key'] ?? null;
        $this->tenant_identifier = $job?->getTenantIdentifier() ?? $payload['tenant_identifier'] ?? null;
        $this->tenant = $job?->getTenant();

        $this->job = $job;
    }

    private function unserializeToJob(string $object): object
    {
        if (!str_starts_with($object, 'O:')) {
            $object = App::make(Encrypter::class)->decrypt($object);
        }

        $stdClassObj = preg_replace('/^O:\d+:"[^"]++"/', 'O:'.strlen(TenancyJob::class).':"'.TenancyJob::class.'"', $object);

        return unserialize($stdClassObj, ['allowed_classes' => [Job::class, TenancyJob::class]]);
    }
}
