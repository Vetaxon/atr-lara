<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Request;

class ExportController extends Controller
{
    public function welcome()
    {
        return view('hello');
    }

    /**
     * View all students found in the database
     */
    public function viewStudents()
    {
        $students = Student::with('course');
        return view('view_students', ['students' => $students->get()->toArray(), 'map' => $this->getStudentsTableMap()]);
    }

    /**
     * Exports all student data to a CSV file
     */
    public function exportStudentsToCSV() : StreamedResponse
    {
        $ids = Request::query('ids');
        $query = Student::with('course');

        if (!empty($ids)) {
            $query->whereIn('id', array_map(function ($id) {
                return (int) $id;
            }, $ids));
        }

        return $this->getStudentsTableCsvStream($query, $this->getStudentsTableMap());
    }

    /**
     * Exports the total amount of students that are taking each course to a CSV file
     */
    public function exportCourseAttendanceToCSV()
    {
        $ids = Request::query('ids');
        $tableMap = [
            'Course name' => function(array $data) : string {return $data['course_name'];},
            'NO students' => function(array $data) : string {return $data['count'];},
        ];

        $query = Course::query()
            ->selectRaw('courses.course_name, COUNT(students.id) as count')
            ->join('students', 'students.course_id', '=', 'courses.id')
            ->groupBy('courses.course_name')
            ->orderBy('courses.course_name');

        if (!empty($ids)) {
            $query->whereIn('students.id', array_map(function ($id) {
                return (int) $id;
            }, $ids));
        }

        return $this->getStudentsTableCsvStream($query, $tableMap);
    }


    /**
     * @return array
     */
    protected function getStudentsTableMap() : array
    {
        return [
            'Forename' => function(array $student) : string {return $student['firstname'];},
            'Surname' => function(array $student) : string {return $student['surname'];},
            'Email' => function(array $student) : string {return $student['email'];},
            'University' => function(array $student) : string {return $student['course']['university'] ?? '';},
            'Course' => function(array $student) : string {return $student['course']['course_name'] ?? '';},
        ];
    }

    /**
     * @param Builder $query
     * @param array $tableMap
     * @return StreamedResponse
     */
    protected function getStudentsTableCsvStream(Builder $query, array $tableMap) : StreamedResponse
    {
        return new StreamedResponse(function() use ($tableMap, $query) {

            $handle = fopen('php://output', 'w');
            fputcsv($handle, array_keys($tableMap));

            $query->chunk(5, function (Collection $data) use ($handle, $tableMap) {
                foreach ($data->toArray() as $dataPrepared) {
                    fputcsv($handle, array_map(function (callable $call) use ($dataPrepared) {
                        return $call($dataPrepared);
                    }, $tableMap));
                }
            });

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment',
            'Content-Filename' => 'export.csv',
        ]);
    }
}
