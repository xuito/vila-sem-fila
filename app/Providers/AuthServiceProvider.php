<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\User;
use App\Models\Permission;

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
        $permissions = Permission::with('roles')->get();

        foreach($permissions as $permission)
        {
            Gate::define($permission->name, function(User $user) use ($permission)
            {
                foreach($user->roles as $role)
                {
                    if($role->name == 'super')
                    {
                        return true;
                    }
                    if($role->permissions->contains('name', $permission->name))
                    {
                        return true;
                    }
                }
                return false;
            });
        }
    }
}
