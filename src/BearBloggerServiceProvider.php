<?php

namespace Azurinspire\BearBlogger;

use Azurinspire\BearBlogger\Commands\BearBloggerCommand;
use Illuminate\Support\ServiceProvider;

class BearBloggerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/bear-blogger.php' => config_path('bear-blogger.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/bear-blogger'),
            ], 'views');

            if (! class_exists('CreatePackageTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_bear_blogger_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_bear_blogger_table.php'),
                ], 'migrations');
            }

            $this->commands([
                BearBloggerCommand::class,
            ]);
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bear-blogger');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/bear-blogger.php', 'bear-blogger');
    }
}
