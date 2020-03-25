<?php

namespace App\Services\Responses\Contracts;

use Symfony\Component\HttpFoundation\StreamedResponse;

interface StudentsCoursesCsvResponseInterface
{
    public function studentsToCSV(array $filters = []) : StreamedResponse;

    public function courseAttendanceToCSV(array $filters = []) : StreamedResponse;
}
