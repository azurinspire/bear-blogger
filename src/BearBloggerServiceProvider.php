<?php

namespace AzurInspire\BearBlogger;

use AzurInspire\BearBlogger\Commands\BearBloggerCommand;
use AzurInspire\BearBlogger\Http\Controllers\FetchController;
use AzurInspire\BearBlogger\Http\Controllers\ImportController;
use AzurInspire\BearBlogger\Http\Controllers\PromoteController;
use AzurInspire\BearBlogger\Http\Controllers\PublishController;
use AzurInspire\BearBlogger\Http\Controllers\UploadController;
use AzurInspire\BearBlogger\Http\Livewire\PhotoImport;
use AzurInspire\BearBlogger\View\Components\BlogAdmin;
use AzurInspire\BearBlogger\View\Components\Gallery;
use AzurInspire\BearBlogger\View\Components\ImageUploader;
use AzurInspire\BearBlogger\View\Components\PromoteHero;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

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
        $this->loadViewComponentsAs('bear-blogger', [
            BlogAdmin::class,
            ImageUploader::class,
            PromoteHero::class,
            Gallery::class,
        ]);

        Livewire::component('bear-blogger::photo-import', PhotoImport::class);

        Route::macro('bearBlogger', function () {
            Route::prefix('bear-blogger')->group(function () {
                Route::post('fetch', [FetchController::class, 'store'])->name('bear-blogger.fetch.all');
                Route::post('{blogPost:slug}/fetch', [FetchController::class, 'update'])->name('bear-blogger.fetch');
                Route::post('{blogPost:slug}/publish', [PublishController::class, 'store'])->name('bear-blogger.publish');
                Route::post('{blogPost:slug}/promote/{media:id}', [PromoteController::class, 'update'])->name('bear-blogger.promote');
                Route::post('{blogPost:slug}/upload', [UploadController::class, 'store'])->name('bear-blogger.upload');
                Route::delete('{blogPost:slug}/upload', [UploadController::class, 'destroy'])->name('bear-blogger.upload');
                Route::get('import/preview/{directory}/{photo}', [ImportController::class, 'preview'])->name('bear-blogger.import.preview');
            });
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/bear-blogger.php', 'bear-blogger');
    }
}
