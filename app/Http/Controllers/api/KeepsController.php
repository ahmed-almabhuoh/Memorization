<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\AddNewKeepRequest;
use App\Models\Group;
use App\Models\Keeps;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use function Ramsey\Uuid\buildRandomGenerator;

class KeepsController extends Controller
{
    //
    public function index()
    {
        $keeps = Auth::user()->keeps()->paginate();

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

    public function setKeep(AddNewKeepRequest $request, $student_id)
    {
        $student = User::students()->where('id', $student_id)->first();
        if (is_null($student)) {
            return \response()->json([
                'message' => 'Wrong URL, please try again!',
            ], Response::HTTP_BAD_REQUEST);
        }

        $group = Group::whereHas('keeper', function ($query) {
            $query->where('id', Auth::id());
        })->first();

        if (is_null($group)) {
            return \response()->json([
                'message' => 'Keeper does not a group yet!',
            ], Response::HTTP_BAD_REQUEST);
        }

        $student_group = Group::whereHas('students', function ($query) use ($student) {
            $query->where('student_id', $student->id);
        })->where('keeper_id', '=', \auth()->user()->id)->first();

        if (is_null($student_group)) {
            return \response()->json([
                'message' => 'Student does not exists in this group, please try again!',
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($student->status != 'active' || $group->status != 'active') {
            return $this->returnJSON([
                'message' => 'Something is not ready to use, the status not active',
            ], Response::HTTP_BAD_REQUEST);
        }

        $from_juz = $this->loadQuranResources($request->post('from_juz'));
        $to_juz = $this->loadQuranResources($request->post('to_juz'));

        $this->validateKeepInputs($request, $from_juz, $to_juz);

        $keep = new Keeps();
        $keep->from_juz = $request->post('from_juz');
        $keep->to_juz = $request->post('to_juz');
        $keep->from_surah = $request->post('from_surah');
        $keep->to_surah = $request->post('to_surah');
        $keep->from_ayah = $request->post('from_ayah');
        $keep->to_ayah = $request->post('to_ayah');
        $keep->faults = $request->post('faults_number');
        $keep->student_id = $student->id;
        $isCreated = $keep->save();

        /*
         * Submit Student Attendance
         * */
        DB::table('absences')->where([
            ['user_id', '=',  $student_id],
            ['absence_type', '=', 'student'],
            ['absence_date', '=', now()->toDateString()],
        ])->update([
            'status' => true,
        ]);


        return $this->returnJSON([
            'message' => $isCreated ? 'Keep added successfully' : 'Failed to add keeping for student at this moment, please try again!',
        ], $isCreated ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);
    }


    /*
     * Get quran sector for a specific Juz
     * */
    protected function loadQuranResources($juz_id = 30)
    {
        if ($juz_id > 30 || $juz_id < 1) {
            return response()->json([
                'message' => 'Wrong inputs, please try again!',
            ], Response::HTTP_BAD_REQUEST);
        }

        $fileName = 'quran' . $juz_id . '.json';
        $response = 'No data found at the server right now!';

        /*
         * Is file loaded before ?!
         * */
        if (!file_exists(storage_path($fileName))) {
            $response = Http::get('http://api.alquran.cloud/v1/juz/' . $juz_id . '/en.asad');
            file_put_contents(storage_path($fileName), $response);
        }
        return json_decode(file_get_contents(storage_path($fileName)));
    }

    /*
     * Validate quran keep inputs
     * */
    public function validateKeepInputs(Request $request, $from_juz, $to_juz)
    {
        $condition = true;
        if ($condition) {
            $condition = false;
            foreach ($from_juz->data->surahs as $number => $info) {
                if ($number == $request->post('from_surah')) {
                    $condition = true;
                    break;
                }
            }
        } else {
            return $this->returnJSON([
                'message' => 'Incorrect input in (form juz or to juz) field',
            ]);
        }

        //        return \response()->json($request->post('to_surah'));
        if ($condition) {
            $condition = false;
            foreach ($to_juz->data->surahs as $number => $info) {
                if ($number == $request->post('to_surah')) {
                    $condition = true;
                    break;
                }
            }
        } else {
            return $this->returnJSON([
                'message' => 'Incorrect input in (from surah) field',
            ]);
        }


        if ($condition) {
            $condition = false;
            foreach ($from_juz->data->ayahs as $ayah) {
                if ($ayah->numberInSurah == $request->post('from_ayah')) {
                    $condition = true;
                    break;
                }
            }
        } else {
            return $this->returnJSON([
                'message' => 'Incorrect input in (to surah) field',
            ]);
        }


        if ($condition) {
            $condition = false;
            foreach ($to_juz->data->ayahs as $ayah) {
                if ($ayah->numberInSurah == $request->post('to_ayah')) {
                    $condition = true;
                    break;
                }
            }
        } else {
            return $this->returnJSON([
                'message' => 'Incorrect input in (from ayah) field',
            ]);
        }


        if (!$condition) {
            return $this->returnJSON([
                'message' => 'Incorrect input in (to ayah) field',
            ]);
        }
    }


    /*
     * Customize returned JSON object
     * */
    public function returnJSON($array, $code = 200)
    {
        return \response()->json($array, $code);
    }
}
