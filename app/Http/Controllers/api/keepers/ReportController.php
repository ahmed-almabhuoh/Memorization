<?php

namespace App\Http\Controllers\api\keepers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    //
    // Get monthly API
    public function monthly()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $firstDay = $currentMonth->format('Y-m-d');
        $lastDay = $currentMonth->endOfMonth()->format('Y-m-d');

        $students = auth()->user()->group->students;
        $report = [];

        // For all students belongs to auth keeper
        foreach ($students as $student) {
            $firstAndLastRows = DB::table('keeps')
                ->where('student_id', $student->id)
                ->whereBetween('created_at', [$firstDay, $lastDay])
                ->orderBy('created_at')
                ->get();


            $first_keep = $firstAndLastRows->first();
            $last_keep = $firstAndLastRows->last();

            // Check if the students sure has keepes
            if (!is_null($first_keep) && !is_null($last_keep)) {
                $from_juz_content = json_decode(file_get_contents(storage_path('quran' . $first_keep->from_juz . '.json')));
                $to_juz_content = json_decode(file_get_contents(storage_path('quran' . $last_keep->to_juz . '.json')));

                $from_juz_data = $from_juz_content->data;
                $to_juz_data = $to_juz_content->data;


                $from_surah_name = '';
                $from_ayah = $first_keep->from_ayah;
                // For loop to get the surah name
                // Get all ayahs
                for ($i = 0; $i < count($from_juz_data->ayahs); ++$i) {

                    // Check the ayah from keeping
                    if ($first_keep->from_ayah == $from_juz_data->ayahs[$i]->numberInSurah) {
                        $surah = $from_juz_data->ayahs[$i]->surah;

                        // Check first keep surah
                        if ($first_keep->from_surah == $surah->number)
                            $from_surah_name = $surah->name;
                    } else {
                        continue;
                    }
                }

                $to_surah_name = '';
                $to_ayah = $first_keep->to_ayah;
                // For loop to get the surah name
                // Get all ayahs
                for ($i = 0; $i < count($to_juz_data->ayahs); ++$i) {

                    // Check the ayah from keeping
                    if ($last_keep->to_ayah == $to_juz_data->ayahs[$i]->numberInSurah) {
                        $surah = $to_juz_data->ayahs[$i]->surah;
                        // return response()->json($surah->number);

                        // Check first keep surah
                        if ($last_keep->to_surah == $surah->number)
                            $to_surah_name = $surah->name;
                        else
                            continue;
                    } else {
                        continue;
                    }
                }

                $absenceCount = DB::table('absences')
                    ->where('absence_type', 'student')
                    ->where('user_id', $student->id)
                    ->where('status', false)
                    // ->whereRaw("DATE_FORMAT(absence_date, '%Y-%m') = '{$currentMonth}'")
                    ->whereBetween('created_at', [$firstDay, $lastDay])
                    ->count();

                $report[] = [
                    $student->fname . ' ' . $student->lname => [
                        'from_surah_name' => $from_surah_name,
                        'from_ayah' => $from_ayah,
                        'to_surah_name' => $to_surah_name,
                        'to_ayah' => $to_ayah,
                        'absence_days' => $absenceCount,
                    ]
                ];
            } else {
                $absenceCount = DB::table('absences')
                    ->where('absence_type', 'student')
                    ->where('user_id', $student->id)
                    ->where('status', false)
                    // ->whereRaw("DATE_FORMAT(absence_date, '%Y-%m') = '{$currentMonth}'")
                    ->whereBetween('created_at', [$firstDay, $lastDay])
                    ->count();

                $report[] = [
                    $student->fname . ' ' . $student->lname => [
                        'from_surah_name' => null,
                        'from_ayah' => null,
                        'to_surah_name' => null,
                        'to_ayah' => null,
                        'absence_days' => $absenceCount,
                    ]
                ];
            }
        }
        return response()->json($report);
    }
}
