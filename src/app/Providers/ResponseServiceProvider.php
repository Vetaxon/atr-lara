<?php

namespace App\Providers;

use App\Services\Responses\Contracts\StreamResponsesFactoryInterface;
use App\Services\Responses\Contracts\StudentsCoursesCsvResponseInterface;
use App\Services\Responses\StreamResponsesFactory;
use App\Services\Responses\StudentsCoursesCsvResponse;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            StreamResponsesFactoryInterface::class,
            StreamResponsesFactory::class
        );
        $this->app->bind(StudentsCoursesCsvResponseInterface::class, function (Container $app) {
            return new StudentsCoursesCsvResponse(
                $app->make(StreamResponsesFactoryInterface::class),
                $app->make('studentsRepository'),
                $app->make('coursesRepository'),
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
