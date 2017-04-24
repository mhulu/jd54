<?php

namespace Star\Permission;

use Illuminate\Support\ServiceProvider;
use Star\Permission\Contracts\Role as RoleContract;
use Star\Permission\Contracts\Permission as PermissionContract;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * @param \Star\Permission\PermissionRegistrar $permissionLoader
     */
    public function boot(PermissionRegistrar $permissionLoader)
    {
        $this->publishes([
            __DIR__.'/permissions_menu.php' => $this->app->configPath().'/'.'permissions_menu.php',
        ], 'config');

        if (! class_exists('CreatePermissionTables')) {
            // Publish the migration
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/migrations/create_permission_tables.php.stub' => $this->app->databasePath().'/migrations/'.$timestamp.'_create_permission_tables.php',
            ], 'migrations');
        }

        $this->registerModelBindings();

        $permissionLoader->registerPermissions();
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/permissions_menu.php',
            'permissions_menu'
        );

    }

    protected function registerModelBindings()
    {
        // $config = $this->app->config['laravel-permission.models'];

        $this->app->bind(PermissionContract::class, \Star\Permission\Models\Permission::class);
        $this->app->bind(RoleContract::class, \Star\Permission\Models\Role::class);
    }

}
