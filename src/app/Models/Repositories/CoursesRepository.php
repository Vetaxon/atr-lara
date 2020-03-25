<?php


namespace App\Models\Repositories;

use App\Models\Course;
use App\Models\Repositories\Abstracts\AbstractRepository;
use App\Models\Repositories\Contracts\CoursesRepositoryInterface;

class CoursesRepository extends AbstractRepository implements CoursesRepositoryInterface
{
    public function getModelName() : string
    {
       return Course::class;
    }
}
