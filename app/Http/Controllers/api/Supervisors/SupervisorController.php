<?php

namespace App\Http\Controllers\api\Supervisors;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\supervisors\StoreSupervisorRequest;
use App\Http\Requests\api\supervisors\UpdateSupervisorRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;

class SupervisorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $superviosrs = User::supervisors()->get();
        return response()->json([
            'supervisors' => $superviosrs,
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupervisorRequest $request)
    {
        $supervisor = new User();
        $supervisor->fname = $request->input('fname');
        $supervisor->sname = $request->input('sname');
        $supervisor->tname = $request->input('tname');
        $supervisor->lname = $request->input('lname');
        $supervisor->phone = $request->input('phone');
        $supervisor->identity_no = $request->input('identity_no');
        $supervisor->email = $request->input('email');
        $supervisor->position = $request->post('position');
        $supervisor->password = Hash::make($request->input('password'));
        $supervisor->gender = $request->input('gender');
        $supervisor->status = $request->input('status');
        $supervisor->local_region = $request->input('local_region');
        $supervisor->description = $request->input('description');
        $image_path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image_path = $file->store('user/supervisors', 'public');
        }
        $supervisor->image = $image_path;
        $isCreated = $supervisor->save();

        return response()->json([
            'message' => $isCreated ? 'Supervisor created successfully' : 'Failed to add the supervisor, please try again later!',
            'admin' => $supervisor,
        ], $isCreated ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $supervisor = User::supervisors()->where('id', $id)->first();
        //
        if (!is_null($supervisor)) {
            return \response()->json([
                'supervisor' => $supervisor,
            ], Response::HTTP_OK);
        } else {
            return \response()->json([
                'message' => 'Supervisor not found!',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $supervisor_id)
    {
        $request->validate([
            'fname' => 'required|string|min:2|max:20',
            'sname' => 'required|string|min:2|max:20',
            'tname' => 'required|string|min:2|max:20',
            'lname' => 'required|string|min:2|max:20',
            'phone' => 'required|string|min:7|max:13|unique:users,phone,' . $supervisor_id,
            'email' => 'required|email|unique:users,email,' . $supervisor_id,
            'gender' => 'required|string|in:male,female',
            'position' => 'required|string|in:supervisor',
            'status' => 'required|string|in:active,draft,blocked',
            'identity_no' => 'required|string|min:9|max:9|unique:users,identity_no,' . $supervisor_id,
            'password' => ['nullable', Password::min(8)->uncompromised()->letters()->numbers(), 'max:25'],
            'image' => 'nullable',
            'local_region' => 'nullable|min:5|max:50',
            'description' => 'nullable|min:10|max:150',
        ]);

        $supervisor = User::supervisors()->where('id', $supervisor_id)->first();
        if (is_null($supervisor)) {
            return \response()->json([
                'message' => 'Supervisor not found!',
            ], Response::HTTP_BAD_REQUEST);
        }

        $supervisor->fname = $request->input('fname');
        $supervisor->sname = $request->input('sname');
        $supervisor->tname = $request->input('tname');
        $supervisor->lname = $request->input('lname');
        $supervisor->phone = $request->input('phone');
        $supervisor->identity_no = $request->input('identity_no');
        $supervisor->email = $request->input('email');
        $supervisor->position = $request->post('position');
        if ($request->post('password')) {
            $supervisor->password = Hash::make($request->input('password'));
        }
        $supervisor->gender = $request->input('gender');
        $supervisor->status = $request->input('status');
        $supervisor->local_region = $request->input('local_region');
        $supervisor->description = $request->input('description');
        $image_path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image_path = $file->store('user/supervisors', 'public');
        }
        $supervisor->image = $image_path;
        $isCreated = $supervisor->save();

        return response()->json([
            'message' => $isCreated ? 'Supervisor updated successfully' : 'Failed to update the supervisor, please try again later!',
            'admin' => $supervisor,
        ], $isCreated ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($supervisor_id)
    {
        $supervisor = User::supervisors()->where('id', $supervisor_id)->first();
        //
        if ($supervisor->delete()) {
            return \response()->json([
                'message' => 'Supervisor deleted successfully!'
            ], Response::HTTP_OK);
        }else {
            return \response()->json([
                'message' => 'Failed to delete supervisor!'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
