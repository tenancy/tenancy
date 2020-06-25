<?php

namespace Tenancy\Tests\Misc\Wingman\Feature;

use Illuminate\Support\Facades\Artisan;
use Tenancy\Misc\Wingman\CLI\Commands\Created;
use Tenancy\Misc\Wingman\CLI\Commands;
use Tenancy\Misc\Wingman\Provider;
use Tenancy\Testing\TestCase;

class ProviderTest extends TestCase
{
    /** @var array */
    protected $additionalProviders = [Provider::class];

    /** @test */
    public function the_commands_are_registered()
    {
        foreach([
            Commands\Created::class,
            Commands\Updated::class,
            Commands\Deleted::class,
            Commands\ListCommand::class,
        ] as $commandClass) {
            $this->assertTrue($this->commandExists($commandClass));
        }
    }

    /**
     * Checks if a command is registered
     *
     * @param string $commandClass
     *
     * @return bool
     */
    private function commandExists(string $commandClass)
    {
        foreach(Artisan::all() as $command){
            if(get_class($command) == $commandClass){
                return true;
            }
        }
        return false;
    }
}
