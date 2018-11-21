<?php

namespace Tenancy\Providers;

 use Illuminate\Contracts\Foundation\Application;
 use Illuminate\Support\ServiceProvider;
 use Tenancy\Identification\Contracts\ResolvesTenants;
 use Tenancy\Identification\Contracts\Tenant;

 class TenantProvider extends ServiceProvider
 {
     protected $defer = true;

     public function boot()
     {
         $this->app->bind(Tenant::class, function (Application $app) {
             /** @var ResolvesTenants $resolver */
             $resolver = $app->make(ResolvesTenants::class);

             return $resolver();
         });
     }

     public function provides()
     {
         return [
             Tenant::class
         ];
     }
 }
