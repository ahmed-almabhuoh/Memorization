<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Keeps;
use App\Models\Test;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentManagementController extends Controller
{
    //
    // Get student absence and attendances
    public function getMyAttendanceAndAbsencesDays()
    {
        $student_id = auth()->user()->id;
        $currentMonth = Carbon::now()->format('Y-m');

        // Get the number of absence days for the current month
        $absenceDays = Absence::where('user_id', $student_id)
            ->where('absence_type', 'student')
            ->whereYear('absence_date', '=', Carbon::now()->year)
            ->whereMonth('absence_date', '=', Carbon::now()->month)
            ->count();

        // Get the number of attendance days for the current month
        $totalDaysInMonth = Carbon::now()->daysInMonth;
        $attendanceDays = $totalDaysInMonth - $absenceDays;

        // Get the remaining days in the month
        $today = Carbon::now()->day;
        $remainingDays = $totalDaysInMonth - $today;

        $absences = Absence::where('user_id', $student_id)
            ->where('absence_type', 'student')->get();

        foreach ($absences as $ab) {
            $dateString = $ab->absence_date;
            $carbonDate = Carbon::createFromFormat('Y-m-d', $dateString);
            $dayOfWeek = $carbonDate->format('l');
            $ab->setAttribute('day_en_name', $dayOfWeek);
            // // $dayOfWeek = $carbonDate->locale('ar')->format('l');
            // $date = Carbon::parse($dateString);
            // setlocale(LC_TIME, 'ar'); // Set the locale to Arabic
            // $arabicDayName = $date->formatLocalized('%A');
            // $ab->setAttribute('day_ar_name', $arabicDayName);

            $carbonDate->locale('ar'); // Set the locale to Arabic
            $arabicDayName = $carbonDate->isoFormat('dddd');
            $ab->setAttribute('day_ar_name', $arabicDayName);
        }

        return [
            'all' => $absences,
            'absenceDays' => $absenceDays,
            'attendanceDays' => $attendanceDays,
            'remainingDays' => $remainingDays,
        ];
    }

    // Get student keeps
    public function getStudentKeeps()
    {
        $student_id = auth()->user()->id;

        $keeps =  Keeps::where('student_id', $student_id)->get();

        foreach ($keeps as $keep) {
            $carbonDate = Carbon::parse($keep->created_at);
            $dayOfWeekEnglish = $carbonDate->isoFormat('dddd'); // English day name
            $dayOfWeekArabic = $carbonDate->locale('ar')->isoFormat('dddd'); // Arabic day name

            $keep->setAttribute('day_en_name', $dayOfWeekEnglish); // Output: Wednesday
            $keep->setAttribute('day_ar_name', $dayOfWeekArabic); // Output: الأربعاء
        }

        return response()->json([
            'keeps' => $keeps,
        ], Response::HTTP_OK);
    }

    // Get student tests
    public function getStudentTests()
    {
        $student_id = auth()->user()->id;
        return response()->json([
            'tests' => Test::where('student_id', $student_id)->with('keeper')->with('questions')->first(),
        ], Response::HTTP_OK);
    }
}
