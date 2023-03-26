<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Rules\ValidStudent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    //
    public function getInfoToGenerateTest($from = 30, $to = 1)
    {
        return $from;
    }


    public function generateTest(Request $request)
    {
        $request->validate([
            'from' => 'required|integer|min:1|max:30',
            'to' => 'required|integer|min:1|max:30',
            'ayahs_no' => 'required|integer|min:1|max:15',
            'type' => 'nullable|in:' . implode(',', Test::TYPE),
            'student_id' => ['required', 'string', 'exists:users,id', new ValidStudent()],
        ]);

        $from = $request->query('from');
        $to = $request->query('to');

        $test_id = DB::table('tests')->insertGetId([
            'type' => $request->query('type') ?? 'single',
            'from' => $from,
            'to' => $to,
            'keeper_id' => auth()->user()->id,
            'student_id' => $request->query('student_id'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $questions = [];
        for ($from; $from <= $to; ++$from) {
            if (!File::exists(storage_path('quran' . $from . '.json'))) {
                $response = Http::get('https://api.alquran.cloud/v1/juz/' . $from . '/ar.asad');
                file_put_contents(storage_path('quran' . $from . '.json'), $response);

                foreach (json_decode(file_get_contents(storage_path('quran' . $from . '.json')))->data->ayahs as $ayah) {
                    $questions[] = $ayah;
                }
            } else {
                foreach (json_decode(file_get_contents(storage_path('quran' . $from . '.json')))->data->ayahs as $ayah) {
                    $questions[] = $ayah;
                }
            }
        }

        $randomObjects = [];

        if ($request->query('ayahs_no') > count($questions))
            return response()->json([
                'message' => 'Invalid question number!',
            ], Response::HTTP_BAD_REQUEST);

        // Use a loop to get 3 random objects
        for ($i = 0; $i < $request->query('ayahs_no') ?? 3; $i++) {
            // Use array_rand() to get a random index
            $randomIndex = array_rand($questions);

            // Use the random index to get the corresponding JSON object
            $randomJsonObject = $questions[$randomIndex];

            DB::table('questions')->insert([
                'ayah' => json_encode($randomJsonObject),
                'test_id' => $test_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Add the random object to the array of random objects
            $randomObjects[] = $randomJsonObject;
        }

        return $randomObjects;
    }
}
