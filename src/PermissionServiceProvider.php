<?php

namespace Almas\Permission;

use Almas\Permission\Support\LSPermissionEngine;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Almas\Permission\Commands\PermissionCommand;

class PermissionServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/permission.php', 'permission');
        $this->app->singleton(LSPermissionEngine::class, fn () => new LSPermissionEngine());
        require_once __DIR__ . '/Helpers/helpers.php';
    }

    public function boot()
    {
        Blade::directive('PermissionLink', function () {
            return "<?php echo e(route('permission.dashboard')); ?>";
        });

        Blade::directive('user_permission', function ($expression) {
            return "<?php if(user_permission($expression)): ?>";
        });

        Blade::directive('end_user_permission', function () {
            return "<?php endif; ?>";
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/permission.php' => config_path('permission.php'),
            ], 'permission-config');

            $this->commands([
                PermissionCommand::class,
            ]);
        }
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'Permission');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }
}
