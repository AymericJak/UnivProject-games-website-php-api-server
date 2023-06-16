<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
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
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Gate::define('delete-commentaire', function ($user, $commentaire) {
            return $user->id === $commentaire->user_id || $user->roles()->pluck('nom')->contains('administrateur') || $user->roles()->pluck('nom')->contains('commentaire-moderateur');
        });
        Gate::define('update-commentaire', function ($user, $commentaire) {
            return $user->id === $commentaire->user_id || $user->roles()->pluck('nom')->contains('administrateur') || $user->roles()->pluck('nom')->contains('commentaire-moderateur');
        });
        Gate::define('store-commentaire', function ($user) {
            return $user->roles()->pluck('id')->contains(1)||
                $user->roles()->pluck('id')->contains(2) ||
                $user->roles()->pluck('id')->contains(3) ||
                $user->roles()->pluck('id')->contains(4) ;
        });
        Gate::define('update-like', function ($user, $like){
            return $user->roles()->pluck('id')->contains(1)|| $user->id == $like->user_id;
        });
    }
}
