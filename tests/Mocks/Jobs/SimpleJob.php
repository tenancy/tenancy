<?php

namespace Tenancy\Tests\Mocks\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Tenancy\Facades\Tenancy;

class SimpleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Collection */
    public $publicCollection;

    /** @var Collection */
    protected $protectedCollection;

    /** @var Collection */
    private $privateCollection;

    /** @var Collection */
    public $anotherPublicCollection;

    /** @var Collection */
    protected $anotherProtectedCollection;

    /** @var Collection */
    private $anotherPrivateCollection;

    public function __construct(
        Collection $collection = null,
        Collection $anotherCollection = null
    ) {
        $this->publicCollection = $collection;
        $this->protectedCollection = $collection;
        $this->priveCollection = $collection;


        $this->anotherPublicCollection = $anotherCollection;
        $this->anotherProtectedCollection = $anotherCollection;
        $this->anotherPriveCollection = $anotherCollection;
    }

    public function handle()
    {
        event('mock.tenant.job', [
            'tenant' => Tenancy::getTenant(),
        ]);
    }
}
