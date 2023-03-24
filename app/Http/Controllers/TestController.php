<?php

namespace App\Http\Controllers;

use App\Jobs\CreateTestQuestiosJob;
use App\Jobs\tests\NotifyUserWhenSubmitMarkJob;
use App\Models\Test;
use App\Models\User;
use App\Notifications\tests\SubmitMarkNotification;
use App\Rules\StudentBelongsToKeeper;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
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
            $test_id = $isCreated = DB::table('tests')->insertGetId([
                'from' => $request->post('from_juz'),
                'to' => $request->post('to_juz'),
                'student_id' => $request->post('student_id'),
                'keeper_id' => \auth()->user()->id,
                'description' => $request->post('description'),
            ]);

            $test = Test::findOrFail($test_id);

            CreateTestQuestiosJob::dispatch($test);

//            return redirect()->route('test.marks.view', [
//                'test_id' => Crypt::encrypt($test_id),
//            ]);

            return \response()->json([
                'message' => $isCreated ? 'Test created successfully, we are start to generate a dynamic questions for you. And we will redirect you after generate the questions automatically' : 'Failed to create test!',
                'isCreated' => $isCreated,
                'test' => $test,
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
    public function show($test_id)
    {
        $test = Test::where('id', $test_id)->with(['questions', 'student'])->first();
        //
//        dd($test);
        return \response()->view('backend.tests.submit-test-mark', [
            'test' => $test,
        ]);
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


    /*
     * Get submit test mark view
     * */
    public function getMarkView($test_id)
    {
        $test = Test::where('id', Crypt::decrypt($test_id))->first();
        return \response()->view('backend.tests.submit-test-mark', [
            'test' => $test,
        ]);
    }

    /*
     * Store questions mark
     * */
    public function storeQuestionMark(Request $request)
    {

        /*
         * We will not handle update the ayah right not
         * */
        $test = Test::where('id', Crypt::decrypt($request->post('test_id')))->with(['questions', 'student'])->first();
        $questions = $test->questions;

        $counter = 1;
        foreach ($questions as $question) {
            $question->faults_no = $request->post('question_mark_' . $counter);
            ++$counter;
            $question->save();
        }

        $student = $test->student;
//        $parent = $student->parent;

        /*
         * Calculate the whole test mark
         * */
        if ($request->post('notify')) {
            $student->notify(new SubmitMarkNotification());
        }

        return \response()->json([
            'message' => 'Mark saved successfully',
        ], Response::HTTP_OK);
    }
}
