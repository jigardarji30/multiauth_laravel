<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
        Passport::tokensCan([
            'super_admin' => 'access super admin backend',
            'admin' => 'access admin app',
            'landlord' => 'access landlord app',
            'renter' => 'access renter app',
            'role' => 'description for role'
        ]);

        Passport::personalAccessClient(1); //<-- this 1 is id of your personal key
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));

        Passport::setDefaultScope(['super_admin,admin,landlord,renter']);
    }
}
