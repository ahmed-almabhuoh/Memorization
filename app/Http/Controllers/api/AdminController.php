<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $admins = User::admin()->get();

        return response()->json([
            'message' => $admins,
            'count' => count($admins),
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|min:2|max:20',
            'sname' => 'nullable|min:2|max:20',
            'tname' => 'nullable|min:2|max:20',
            'lname' => 'required|string|min:2|max:20',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'nullable',
            'gender' => 'required|string|in:male,female',
            'status' => 'required|string|in:active,draft,blocked',
            'identity_no' => 'required|string|min:9|max:9|unique:users,identity_no',
            'password' => ['nullable', Password::min(6)->numbers(), 'max:25'],
            'image' => 'nullable|image',
            'local_region' => 'nullable|min:5|max:50',
            'description' => 'nullable|min:10|max:150'
        ]);

        $admin = new User();
        $admin->fname = $request->input('fname');
        $admin->sname = $request->input('sname');
        $admin->tname = $request->input('tname');
        $admin->lname = $request->input('lname');
        $admin->phone = $request->input('phone');
        $admin->identity_no = $request->input('identity_no');
        $admin->email = $request->input('email');
        $admin->position = 'admin';
        $admin->password = Hash::make($request->input('password'));
        $admin->gender = $request->input('gender');
        $admin->status = $request->input('status');
        $admin->local_region = $request->input('local_region') ?? null;
        $admin->description = $request->input('description') ?? null;
        $image_path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image_path = $file->store('user/admins', 'public');
        }
        $admin->image = $image_path;
        $isCreated = $admin->save();

        return response()->json([
            'message' => $isCreated ? 'Admin created successfully' : 'Failed to add the admin, please try again later!',
            'admin' => $admin,
        ], $isCreated ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $admin = User::admin()->find($id);
        if (is_null($admin))
            return response()->json([
                'message' => 'Admin not found!',
            ], Response::HTTP_BAD_REQUEST);
        //
        return response()->json([
            'admin' => $admin,
            // 'blocks' => $admin->blocks,
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $admin = User::admin()->find($id);
        if (is_null($admin))
            return response()->json([
                'message' => 'Admin not found!',
            ], Response::HTTP_BAD_REQUEST);

        $request->validate(['fname' => 'required|string|min:2|max:20',
            'sname' => 'nullable|min:2|max:20',
            'tname' => 'nullable|min:2|max:20',
            'lname' => 'required|string|min:2|max:20',
            'phone' => 'nullable',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'gender' => 'required|string|in:male,female',
            'status' => 'required|string|in:active,draft,blocked',
            'identity_no' => 'required|string|min:9|max:9|unique:users,identity_no,' . $admin->id,
            'password' => ['nullable', Password::min(6)->numbers(), 'max:25'],
            'image' => 'nullable|image',
            'local_region' => 'nullable|min:5|max:50',
            'description' => 'nullable|min:10|max:150',]);

        $admin->fname = $request->input('fname');
        $admin->sname = $request->input('sname');
        $admin->tname = $request->input('tname');
        $admin->lname = $request->input('lname');
        $admin->phone = $request->input('phone');
        $admin->identity_no = $request->input('identity_no');
        $admin->email = $request->input('email');
        if ($request->input('password')) {
            $admin->password = Hash::make($request->input('password'));
        }
        $admin->gender = $request->input('gender');
        $admin->status = $request->input('status');
        $admin->local_region = $request->input('local_region') ?? null;
        $admin->description = $request->input('description') ?? null;
        $image_path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image_path = $file->store('user/admins', 'public');
            $admin->image = $image_path;
        }
        $isUpdated = $admin->save();

        return response()->json([
            'message' => $isUpdated ? 'Admin updated successfully' : 'Failed to update the admin, please try again later!',
            'admin' => $admin
        ], $isUpdated ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $admin = User::admin()->find($id);
        if (is_null($admin))
            return \response()->json([
                'message' => 'Admin not found!',
            ], Response::HTTP_BAD_REQUEST);

        $isDeleted = $admin->delete();
        //
        return response()->json([
            'message' => $isDeleted ? 'Admin deleted successfully' : 'Failed to delete the admin, please try again later!',
        ], $isDeleted ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }
}
