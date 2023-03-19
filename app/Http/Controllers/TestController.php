<?php

namespace App\Http\Controllers;

use App\Jobs\CreateTestQuestiosJob;
use App\Models\Test;
use App\Models\User;
use App\Rules\StudentBelongsToKeeper;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->view('backend.tests.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        if (!auth()->user()->group()->with('students')->first()) {
            return \response()->view('backend.prevention.no-groups-no-students');
        }

        return response()->view('backend.tests.store', [
            'group_students' => auth()->user()->group()->with('students')->first(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator($request->only([
            'student_id',
            'from_juz',
            'to_juz',
            'description',
        ]), [
            'student_id' => ['required', 'integer', 'exists:users,id'],
            'from_juz' => 'required|integer|min:1|max:30',
            'to_juz' => 'required|integer|min:1|max:30',
            'description' => 'nullable',
        ]);
        //

        /*
         * Check the keepers' students
         * */
        $student_id = $request->post('student_id');
        if (!User::whereHas('groups', function ($query) use ($student_id) {
            $query->whereHas('students', function ($query) use ($student_id) {
                $query->where('id', $student_id);
            });
        })) {
            return abort(403, 'Unauthorized to this action!');
        }

        if (!$validator->fails()) {
            $test = new Test();
            $test->from = $request->post('from_juz');
            $test->to = $request->post('to_juz');
            $test->student_id = $request->post('student_id');
            $test->keeper_id = \auth()->user()->id;
            $test->description = $request->post('description');
            $isCreated = $test->save();

            CreateTestQuestiosJob::dispatch($test);

            return \response()->json([
                'message' => $isCreated ? 'Test created successfully, we are start to generate a dynamic questions for you' : 'Failed to create test!',
            ], $isCreated ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);

        } else {
            return response()->json([
                'message' => $validator->getMessageBag()->first(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Test $test)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Test $test)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Test $test)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Test $test)
    {
        //
    }
}
