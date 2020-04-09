<?php

namespace App\Providers;

use App\Http\Resources\Recipe as RecipeResource;
use Coderello\SocialGrant\Resolvers\SocialUserResolverInterface;
use Illuminate\Support\ServiceProvider;
use App\Resolvers\SocialUserResolver;

class AppServiceProvider extends ServiceProvider
{

    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        SocialUserResolverInterface::class => SocialUserResolver::class,
    ];

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
