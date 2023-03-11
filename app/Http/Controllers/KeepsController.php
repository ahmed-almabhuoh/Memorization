<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Keeps;
use App\Models\User;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class KeepsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($student_id, $group_id)
    {
        $student = User::students()->where('id', Crypt::decrypt($student_id))->first();
        $group = Group::findOrFail(Crypt::decrypt($group_id));
        //
        return response()->view('backend.keeps.index', [
            'student' => $student,
            'group' => $group,
            'keeps' => $student->keeps()->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($student_id, $group_id)
    {
        $student = User::students()->where('id', Crypt::decrypt($student_id))->first();
        $group = Group::findOrFail(Crypt::decrypt($group_id));
        //
        if (is_null($group) || is_null($student))
            return response()->json([
                'message' => 'Wrong URL, please try again!'
            ], Response::HTTP_BAD_REQUEST);

//        $juzs_response = Http::get('http://api.alquran.cloud/v1/juz/30/en.asad');

        return \response()->view('backend.keeps.store', [
            'student' => $student,
            'group' => $group,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = json_decode(file_get_contents(storage_path('quran' . $request->post('from_juz') . '.json')));
        $validator = Validator($request->only([
            'student_id',
            'group_id',
            'from_juz',
            'to_juz',
            'from_surah',
            'to_surah',
            'from_ayah',
            'to_ayah',
            'fault_number',
        ]), [
            'student_id' => 'required|integer|exists:users,id',
            'group_id' => 'required|integer|exists:groups,id',
            'from_juz' => 'required|integer',
            'to_juz' => 'required|integer',
            'to_surah' => 'required|integer',
            'from_surah' => 'required|integer',
            'from_ayah' => 'required|integer',
            'to_ayah' => 'required|integer',
            'fault_number' => 'required|integer|min:0',
        ]);
        //
        if (! $validator->failed()) {
            $student = User::where('id', Crypt::decrypt($request->post('student_id')))->first();
            $group = Group::where('id', Crypt::decrypt($request->post('group_id')))->first();

            DB::table('keeps')->insert([
                'student_id' => $student->id,
                'group_id' => $group->id,
                'from_juz' => $request->post('from_juz'),
                'to_juz' => $request->post('to_juz'),
                'to_surah' => $request->post('to_surah'),
                'from_surah' => $request->post('from_surah'),
                'from_ayah' => $request->post('from_ayah'),
                'to_ayah' => $request->post('to_ayah'),
                'faults' => $request->post('fault_number'),
            ]);

            return \response()->json([
                'message' => 'Keep add successfully for student',
            ], Response::HTTP_CREATED);
        }else {
            return \response()->json([
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Keeps $keeps)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Keeps $keeps)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Keeps $keeps)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Keeps $keeps)
    {
        //
    }
}
