<?php

namespace App\Http\Controllers;

use App\Models\Repositories\StudentsRepository;
use App\Models\Repositories\CoursesRepository;
use App\Services\Responses\Contracts\StudentsCoursesCsvResponseInterface;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Request;

/**
 * Class ExportController
 * @package App\Http\Controllers
 * @property StudentsRepository $studentsRepository
 * @property CoursesRepository $coursesRepository
 */
class ExportController extends CoreAbstractController
{
    /**
     * View all students found in the database
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function welcome()
    {
        return $this->getView();
    }

    /**
     * View all students found in the database
     * @param StudentsCoursesCsvResponseInterface $coursesCsvResponse
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewStudents(StudentsCoursesCsvResponseInterface $coursesCsvResponse)
    {
        $students = $this->studentsRepository->getCollection(function (Builder $query) {
            return $query->with('course');
        });
        return $this->getView(null, ['students' => $students->toArray(), 'map' => $coursesCsvResponse->getStudentsTableMap()]);
    }

    /**
     * Exports all student data to a CSV file
     * @param StudentsCoursesCsvResponseInterface $coursesCsvResponse
     * @return StreamedResponse
     */
    public function exportStudentsToCSV(StudentsCoursesCsvResponseInterface $coursesCsvResponse) : StreamedResponse
    {
        return $coursesCsvResponse->studentsToCSV(['ids' => Request::query('ids')]);
    }

    /**
     * Exports the total amount of students that are taking each course to a CSV file
     * @param StudentsCoursesCsvResponseInterface $coursesCsvResponse
     * @return StreamedResponse
     */
    public function exportCourseAttendanceToCSV(StudentsCoursesCsvResponseInterface $coursesCsvResponse): StreamedResponse
    {
        return $coursesCsvResponse->courseAttendanceToCSV(['ids' => Request::query('ids')]);
    }
}
