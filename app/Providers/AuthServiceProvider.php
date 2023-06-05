<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\API\RoleController;
use App\Policies\CategoryPolicy;
use App\Policies\CommentPolicy;
use App\Policies\ProductPolicy;
use App\Policies\RolePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Product' => 'App\Policies\ProductPolicy',
        'App\Models\Category' => 'App\Policies\CategoryPolicy',
        'App\Models\Role' => 'App\Policies\RolePolicy',
        'App\Models\Comment' => 'App\Policies\CommentPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('read-product' , [ProductPolicy::class , 'viewAny']);
        Gate::define('create-product' , [ProductPolicy::class , 'create']);
        Gate::define('update-product' , [ProductPolicy::class , 'update']);
        Gate::define('delete-product' , [ProductPolicy::class , 'delete']);

        Gate::define('read-category' , [CategoryPolicy::class , 'create']);
        Gate::define('update-category' , [CategoryPolicy::class , 'update']);
        Gate::define('delete-category' , [CategoryPolicy::class , 'delete']);

        Gate::define('read-role' , [RolePolicy::class , 'viewAny']);
        Gate::define('create-role' , [RolePolicy::class , 'create']);
        Gate::define('update-role' , [RolePolicy::class , 'update']);
        Gate::define('delete-role' , [RolePolicy::class , 'delete']);

        Gate::define('read-comment' , [CommentPolicy::class , 'viewAny']);
        Gate::define('update-comment' , [CommentPolicy::class , 'update']);
        Gate::define('delete-comment' , [CommentPolicy::class , 'delete']);


    }
}
