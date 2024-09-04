<?php
namespace Storhn\Reporter\Providers;

use Illuminate\Support\ServiceProvider;

class ReporterServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/reporter.php' => config_path('reporter.php'),
        ], 'config-reporter');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/reporter.php', 'reporter');
    }
}
