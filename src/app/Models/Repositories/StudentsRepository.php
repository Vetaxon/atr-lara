<?php


namespace App\Models\Repositories;

use App\Models\Repositories\Abstracts\AbstractRepository;
use App\Models\Repositories\Contracts\StudentsRepositoryInterface;
use App\Models\Student;

class StudentsRepository extends AbstractRepository implements StudentsRepositoryInterface
{
    public function getModelName() : string
    {
       return Student::class;
    }
}
