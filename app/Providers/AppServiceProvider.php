<?php

namespace App\Providers;

use App\Http\Resources\Recipe as RecipeResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        RecipeResource::withoutWrapping();
        /*
         * or you can disable every JsonResource wrapping if you want
         * */
    }
}
