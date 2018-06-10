<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; /* MariaDB fix */

class AppServiceProvider extends ServiceProvider
{
    /** 
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {   
        Schema::defaultStringLength(191); /* MariaDB fix */
    }

    /** 
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {   
        //  
    }   
}