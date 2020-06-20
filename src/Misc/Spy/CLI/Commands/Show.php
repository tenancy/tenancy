<?php

namespace Tenancy\Misc\Spy\CLI\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Tenancy\Misc\Help\Contracts\Package;
use Tenancy\Misc\Help\Contracts\ResolvesPackages;

class Show extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenancy-spy:show
                            {--installed : Filter to only show installed packages}
                            {--registered : Filter to only show registered packages}
                            {--configured : Filter to only show configured packages}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Spy will look at all the known Tenancy packages and gather information on your setup';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $packages = App::make(ResolvesPackages::class)->getPackages();

        if($this->option('installed')){
            $packages = $packages->filter(function (Package $package) {
                return $package->isInstalled();
            });
        }

        if($this->option('registered')){
            $packages = $packages->filter(function (Package $package) {
                return $package->isRegistered();
            });
        }

        if($this->option('configured')){
            $packages = $packages->filter(function (Package $package) {
                return $package->isConfigured();
            });
        }

        $this->table(
            ['Package', 'Installed?', 'Registered?', 'Configured?'],
            $packages->map(function (Package $package){
                return [
                    $package->getName(),
                    $package->isInstalled() ? '<fg=green>✓</>' : '<fg=red>X</>',
                    $package->isRegistered() ? '<fg=green>✓</>' : '<fg=red>X</>',
                    $package->isConfigured() ? '<fg=green>✓</>' : '<fg=red>X</>',
                ];
            })
        );
    }
}
