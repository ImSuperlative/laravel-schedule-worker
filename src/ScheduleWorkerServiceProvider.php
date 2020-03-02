<?php

namespace imsuperlative\scheduleWorker;

use Illuminate\Support\ServiceProvider;

class ScheduleWorkerServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->publishes([
            __DIR__.'/config/scheduleWorker.php' => config_path('scheduleWorker.php'),
        ]);
    }

    public function provides()
    {
    }

    public function boot()
    {
    }
}
