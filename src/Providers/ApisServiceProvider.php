<?php
namespace NexaMerchant\Apis\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Webkul\Shop\Http\Middleware\AuthenticateCustomer;
use Webkul\Shop\Http\Middleware\Currency;
use Webkul\Shop\Http\Middleware\Locale;
use Webkul\Shop\Http\Middleware\Theme;
use NexaMerchant\Apis\Http\Middleware\AssignRequestId;

class ApisServiceProvider extends ServiceProvider
{
    private $version = null;
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        Route::middleware('web')->group(__DIR__ . '/../Routes/web.php');
        Route::middleware('api')->group(__DIR__ . '/../Routes/api.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'Apis');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'Apis');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $router->aliasMiddleware('assign_request_id', AssignRequestId::class);
        

        /*
        $this->app->register(EventServiceProvider::class);
        */

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../Resources/views' => $this->app->resourcePath('themes/default/views'),
            ], 'Apis');
        }

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
        $this->registerConfig();
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php', 'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php',
            'acl'
        );

        
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/Apis.php', 'Apis'
        );
        
    }

    /**
     * Register the console commands of this package.
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \NexaMerchant\Apis\Console\Commands\Install::class,
                \NexaMerchant\Apis\Console\Commands\UnInstall::class,
            ]);
        }
    }
}
