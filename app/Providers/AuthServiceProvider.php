<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;


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

        if(!$this->app->runningInConsole()){
            // không phải ứng dụng chạy trong cửa sổ lệnh thì mới thực hiện kiểm tra
            foreach (Permission::all() as $permission){
                Gate::define($permission->name, function ($user) use ($permission) {
                    return $user->hasPermission($permission);
                });
            }
        }
    }
}
