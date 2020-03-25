<?php

namespace App\Providers;

use App\Models\Repositories\CoursesRepository;
use App\Models\Repositories\StudentsRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('coursesRepository', CoursesRepository::class);
        $this->app->singleton('studentsRepository' , StudentsRepository::class);
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
