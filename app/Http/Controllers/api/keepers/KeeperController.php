<?php

namespace App\Http\Controllers\api\keepers;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\keepers\StoreNewKeeper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;

class KeeperController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $keepers = User::keepers()->get();
        //
        return response()->json([
            'keepers' => $keepers,
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewKeeper $request)
    {
        $keeper = new User();
        $keeper->fname = $request->input('fname');
        $keeper->sname = $request->input('sname');
        $keeper->tname = $request->input('tname');
        $keeper->lname = $request->input('lname');
        $keeper->phone = $request->input('phone');
        $keeper->identity_no = $request->input('identity_no');
        $keeper->email = $request->input('email');
        $keeper->position = $request->post('position');
        $keeper->password = Hash::make($request->input('password'));
        $keeper->gender = $request->input('gender');
        $keeper->status = $request->input('status');
        $keeper->local_region = $request->input('local_region');
        $keeper->description = $request->input('description');
        $image_path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image_path = $file->store('user/keepers', 'public');
        }
        $keeper->image = $image_path;
        $isCreated = $keeper->save();

        return response()->json([
            'message' => $isCreated ? 'Keeper created successfully' : 'Failed to add the keeper, please try again later!',
            'admin' => $keeper,
        ], $isCreated ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);
    }

    /**
     * Display the specified resource.
     */
    public function show($keeper_id)
    {
        $keeper = User::keepers()->where('id', $keeper_id)->first();
        //
        if (! is_null($keeper)) {
            return \response()->json([
                'keeper' => $keeper
            ], Response::HTTP_OK);
        }else {
            return \response()->json([
                'message' => 'Keeper not found!',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $keeper_id)
    {
        $request->validate([
            'fname' => 'required|string|min:2|max:20',
            'sname' => 'nullable|min:2|max:20',
            'tname' => 'nullable|min:2|max:20',
            'lname' => 'required|string|min:2|max:20',
            'phone' => 'nullable',
            'email' => 'required|email|unique:users,email,' . $keeper_id,
            'gender' => 'required|string|in:male,female',
            'position' => 'required|string|in:keeper',
            'status' => 'required|string|in:active,draft,blocked',
            'identity_no' => 'required|string|min:9|max:9|unique:users,identity_no,' . $keeper_id,
            'password' => ['nullable', Password::min(6)->numbers(), 'max:25'],
            'image' => 'nullable',
            'local_region' => 'nullable|min:5|max:50',
            'description' => 'nullable|min:10|max:150',
        ]);

        $keeper = User::keepers()->where('id', $keeper_id)->first();
        if (is_null($keeper)) {
            return \response()->json([
                'message' => 'Keeper not found!',
            ], Response::HTTP_BAD_REQUEST);
        }

        $keeper->fname = $request->input('fname');
        $keeper->sname = $request->input('sname');
        $keeper->tname = $request->input('tname');
        $keeper->lname = $request->input('lname');
        $keeper->phone = $request->input('phone');
        $keeper->identity_no = $request->input('identity_no');
        $keeper->email = $request->input('email');
        $keeper->position = $request->post('position');
        if ($request->post('password')) {
            $keeper->password = Hash::make($request->input('password'));
        }
        $keeper->gender = $request->input('gender');
        $keeper->status = $request->input('status');
        $keeper->local_region = $request->input('local_region');
        $keeper->description = $request->input('description');
        $image_path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image_path = $file->store('user/keepers', 'public');
        }
        $keeper->image = $image_path;
        $isCreated = $keeper->save();

        return response()->json([
            'message' => $isCreated ? 'Keeper updated successfully' : 'Failed to update the keeper, please try again later!',
            'admin' => $keeper,
        ], $isCreated ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($keeper_id)
    {
        $keeper = User::keepers()->where('id', $keeper_id)->first();
        //
        if ($keeper->delete()) {
            return \response()->json([
                'message' => 'Keeper deleted successfully!'
            ], Response::HTTP_OK);
        }else {
            return \response()->json([
                'message' => 'Failed to delete keeper!'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
