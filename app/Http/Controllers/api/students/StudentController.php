<?php

namespace App\Http\Controllers\api\students;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\students\StoreNewStudentRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = User::students()->get();
        //
        return response()->json([
            'students' => $students,
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewStudentRequest $request)
    {
        $student = new User();
        $student->fname = $request->input('fname');
        $student->sname = $request->input('sname');
        $student->tname = $request->input('tname');
        $student->lname = $request->input('lname');
        $student->phone = $request->input('phone');
        $student->identity_no = $request->input('identity_no');
        $student->email = $request->input('email');
        $student->position = $request->post('position');
        $student->password = Hash::make($request->input('password'));
        $student->gender = $request->input('gender');
        $student->status = $request->input('status');
        $student->local_region = $request->input('local_region');
        $student->description = $request->input('description');
        $image_path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image_path = $file->store('user/students', 'public');
        }
        $student->image = $image_path;
        $isCreated = $student->save();

        return response()->json([
            'message' => $isCreated ? 'Student created successfully' : 'Failed to add the student, please try again later!',
            'admin' => $student,
        ], $isCreated ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);
    }

    /**
     * Display the specified resource.
     */
    public function show($student_id)
    {
        $student = User::students()->where('id', $student_id)->first();
        //
        if (! is_null($student)) {
            return \response()->json([
                'student' => $student
            ], Response::HTTP_OK);
        }else {
            return \response()->json([
                'message' => 'Student not found!',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $student_id)
    {
        $request->validate([
            'fname' => 'required|string|min:2|max:20',
            'sname' => 'nullable|min:2|max:20',
            'tname' => 'nullable|min:2|max:20',
            'lname' => 'required|string|min:2|max:20',
            'phone' => 'nullable',
            'email' => 'nullable|unique:users,email,' . $student_id,
            'gender' => 'required|string|in:male,female',
            'position' => 'required|string|in:student',
            'status' => 'required|string|in:active,draft,blocked',
            'identity_no' => 'required|string|min:9|max:9|unique:users,identity_no,' . $student_id,
            'password' => ['nullable', Password::min(6)->numbers(), 'max:25'],
            'image' => 'nullable',
            'local_region' => 'nullable|min:5|max:50',
            'description' => 'nullable|min:10|max:150',
        ]);

        $student = User::students()->where('id', $student_id)->first();
        if (is_null($student)) {
            return \response()->json([
                'message' => 'Student not found!',
            ], Response::HTTP_BAD_REQUEST);
        }

        $student->fname = $request->input('fname');
        $student->sname = $request->input('sname');
        $student->tname = $request->input('tname');
        $student->lname = $request->input('lname');
        $student->phone = $request->input('phone');
        $student->identity_no = $request->input('identity_no');
        $student->email = $request->input('email');
        $student->position = $request->post('position');
        if ($request->post('password')) {
            $student->password = Hash::make($request->input('password'));
        }
        $student->gender = $request->input('gender');
        $student->status = $request->input('status');
        $student->local_region = $request->input('local_region');
        $student->description = $request->input('description');
        $image_path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image_path = $file->store('user/students', 'public');
        }
        $student->image = $image_path;
        $isCreated = $student->save();

        return response()->json([
            'message' => $isCreated ? 'Student updated successfully' : 'Failed to update the student, please try again later!',
            'admin' => $student,
        ], $isCreated ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($student_id)
    {
        $student = User::students()->where('id', $student_id)->first();
        //
        if ($student->delete()) {
            return \response()->json([
                'message' => 'Student deleted successfully!'
            ], Response::HTTP_OK);
        }else {
            return \response()->json([
                'message' => 'Failed to delete student!'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
