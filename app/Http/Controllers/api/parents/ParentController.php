<?php

namespace App\Http\Controllers\api\parents;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\parents\StoreNewParentRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;

class ParentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parents = User::parents()->get();
        //
        return response()->json([
            'parents' => $parents,
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewParentRequest $request)
    {
        $parent = new User();
        $parent->fname = $request->input('fname');
        $parent->sname = $request->input('sname');
        $parent->tname = $request->input('tname');
        $parent->lname = $request->input('lname');
        $parent->phone = $request->input('phone');
        $parent->identity_no = $request->input('identity_no');
        $parent->email = $request->input('email');
        $parent->position = $request->post('position');
        $parent->password = Hash::make($request->input('password'));
        $parent->gender = $request->input('gender');
        $parent->status = $request->input('status');
        $parent->local_region = $request->input('local_region');
        $parent->description = $request->input('description');
        $image_path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image_path = $file->store('user/parents', 'public');
        }
        $parent->image = $image_path;
        $isCreated = $parent->save();

        return response()->json([
            'message' => $isCreated ? 'Parent created successfully' : 'Failed to add the parent, please try again later!',
            'admin' => $parent,
        ], $isCreated ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);
    }

    /**
     * Display the specified resource.
     */
    public function show($parent_id)
    {
        $parent = User::parents()->where('id', $parent_id)->first();
        //
        if (! is_null($parent)) {
            return \response()->json([
                'parent' => $parent
            ], Response::HTTP_OK);
        }else {
            return \response()->json([
                'message' => 'Parent not found!',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $parent_id)
    {
        $request->validate([
            'fname' => 'required|string|min:2|max:20',
            'sname' => 'required|string|min:2|max:20',
            'tname' => 'required|string|min:2|max:20',
            'lname' => 'required|string|min:2|max:20',
            'phone' => 'required|string|min:7|max:13|unique:users,phone,' . $parent_id,
            'email' => 'required|email|unique:users,email,' . $parent_id,
            'gender' => 'required|string|in:male,female',
            'position' => 'required|string|in:parent',
            'status' => 'required|string|in:active,draft,blocked',
            'identity_no' => 'required|string|min:9|max:9|unique:users,identity_no,' . $parent_id,
            'password' => ['nullable', Password::min(8)->uncompromised()->letters()->numbers(), 'max:25'],
            'image' => 'nullable',
            'local_region' => 'nullable|min:5|max:50',
            'description' => 'nullable|min:10|max:150',
        ]);

        $parent = User::parents()->where('id', $parent_id)->first();
        if (is_null($parent)) {
            return \response()->json([
                'message' => 'Parent not found!',
            ], Response::HTTP_BAD_REQUEST);
        }

        $parent->fname = $request->input('fname');
        $parent->sname = $request->input('sname');
        $parent->tname = $request->input('tname');
        $parent->lname = $request->input('lname');
        $parent->phone = $request->input('phone');
        $parent->identity_no = $request->input('identity_no');
        $parent->email = $request->input('email');
        $parent->position = $request->post('position');
        if ($request->post('password')) {
            $parent->password = Hash::make($request->input('password'));
        }
        $parent->gender = $request->input('gender');
        $parent->status = $request->input('status');
        $parent->local_region = $request->input('local_region');
        $parent->description = $request->input('description');
        $image_path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image_path = $file->store('user/parents', 'public');
        }
        $parent->image = $image_path;
        $isCreated = $parent->save();

        return response()->json([
            'message' => $isCreated ? 'Parent updated successfully' : 'Failed to update the parent, please try again later!',
            'admin' => $parent,
        ], $isCreated ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($parent_id)
    {
        $parent = User::parents()->where('id', $parent_id)->first();
        //
        if ($parent->delete()) {
            return \response()->json([
                'message' => 'Parent deleted successfully!'
            ], Response::HTTP_OK);
        }else {
            return \response()->json([
                'message' => 'Failed to delete parent!'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
