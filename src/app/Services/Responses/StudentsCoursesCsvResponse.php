<?php

namespace App\Services\Responses;

use App\Models\Repositories\Contracts\CoursesRepositoryInterface;
use App\Models\Repositories\Contracts\StudentsRepositoryInterface;
use App\Models\Repositories\CoursesRepository;
use App\Models\Repositories\StudentsRepository;
use App\Services\Responses\Contracts\StreamResponsesFactoryInterface;
use App\Services\Responses\Contracts\StudentsCoursesCsvResponseInterface;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentsCoursesCsvResponse implements StudentsCoursesCsvResponseInterface
{
    /**
     * @var StreamResponsesFactoryInterface|StreamResponsesFactory
     */
    protected $streamResponsesFactory;

    /** @var StudentsRepositoryInterface|StudentsRepository  */
    protected $studentsRepository;

    /** @var CoursesRepositoryInterface|CoursesRepository  */
    protected $coursesRepository;

    /**
     * StudentsCoursesCsvResponse constructor.
     * @param StreamResponsesFactoryInterface $streamResponsesFactory
     * @param StudentsRepositoryInterface $studentsRepository
     * @param CoursesRepositoryInterface $coursesRepository
     */
    public function __construct(
        StreamResponsesFactoryInterface $streamResponsesFactory,
        StudentsRepositoryInterface $studentsRepository,
        CoursesRepositoryInterface $coursesRepository
    )
    {
        $this->streamResponsesFactory = $streamResponsesFactory;
        $this->coursesRepository = $coursesRepository;
        $this->studentsRepository = $studentsRepository;
    }

    /**
     * @param array $filters
     * @return StreamedResponse
     */
    public function studentsToCSV(array $filters = []) : StreamedResponse
    {
        $query = $this->studentsRepository->getCollection(function (Builder $query) {
            return $query->with('course');
        }, true);

        if (!empty($filters['ids'])) {
            $query->whereIn('id', array_map(function ($id) {
                return (int) $id;
            }, $filters['ids']));
        }

        return $this->streamResponsesFactory->getCsvQueryStream($query, $this->getStudentsTableMap());
    }


    public function courseAttendanceToCSV(array $filters = []) : StreamedResponse
    {
        $query = $this->coursesRepository->getCollection(function (Builder $query) {
            return $query->selectRaw('courses.course_name, COUNT(students.id) as count')
                ->join('students', 'students.course_id', '=', 'courses.id')
                ->groupBy('courses.course_name')
                ->orderBy('courses.course_name');
        }, true);

        if (!empty($filters['ids'])) {
            $query->whereIn('students.id', array_map(function ($id) {
                return (int) $id;
            }, $filters['ids']));
        }

        return $this->streamResponsesFactory->getCsvQueryStream($query, [
            'Course name' => function(array $data) : string {return $data['course_name'];},
            'NO students' => function(array $data) : string {return $data['count'];},
        ]);

    }

    /**
     * @return array
     */
    public function getStudentsTableMap() : array
    {
        return [
            'Forename' => function(array $student) : string {return $student['firstname'];},
            'Surname' => function(array $student) : string {return $student['surname'];},
            'Email' => function(array $student) : string {return $student['email'];},
            'University' => function(array $student) : string {return $student['course']['university'] ?? '';},
            'Course' => function(array $student) : string {return $student['course']['course_name'] ?? '';},
        ];
    }
}
