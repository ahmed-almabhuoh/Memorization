<?php

namespace App\Http\Controllers;

use App\Events\CreateBlockUserEvent;
use App\Models\Block;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;

class KeeperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $keepers = User::where('position', 'keeper')->paginate();

        return response()->view('backend.keepers.index', [
            'keepers' => $keepers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return response()->view('backend.keepers.store', [
            'position' => 'keeper',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator($request->only([
            'fname',
            'sname',
            'tname',
            'lname',
            'phone',
            'email',
            'gender',
            'status',
            'identity_no',
            'password',
            'image',
            'local_region',
            'description',
//            'position',
        ]), [
            'fname' => 'required|string|min:2|max:20',
            'sname' => 'required|string|min:2|max:20',
            'tname' => 'required|string|min:2|max:20',
            'lname' => 'required|string|min:2|max:20',
            'phone' => 'required|string|min:7|max:13|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'gender' => 'required|string|in:male,female',
            'status' => 'required|string|in:active,draft,blocked',
            'identity_no' => 'required|string|min:9|max:9|unique:users,identity_no',
            'password' => ['required', Password::min(8)->uncompromised()->letters()->numbers(), 'string', 'max:25'],
            'image' => 'nullable',
            'local_region' => 'nullable|min:5|max:50',
            'description' => 'nullable|min:10|max:150',
//            'position' => 'required|string|position:keeper',
        ]);
        //
        if (!$validator->fails()) {
            $user = new User();
            $user->fname = $request->post('fname');
            $user->sname = $request->post('sname');
            $user->tname = $request->post('tname');
            $user->lname = $request->post('lname');
            $user->phone = $request->post('phone');
            $user->identity_no = $request->post('identity_no');
            $user->email = $request->post('email');
            $user->password = Hash::make($request->post('password'));
            $user->gender = $request->post('gender');
            $user->status = $request->post('status');
            $user->position = 'keeper';
            $user->local_region = $request->post('local_region');
            $user->description = $request->post('description');
            $image_path = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $image_path = $file->store('user', 'public');
            }
            $user->image = $image_path;
            $isCreated = $user->save();

            event(new CreateBlockUserEvent($request, $user));

            return response()->json([
                'message' => $isCreated
                    ? 'keeper added successfully.'
                    : 'Failed to add keeper, please try again!'
            ], $isCreated
                ? Response::HTTP_CREATED
                : Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'message' => $validator->getMessageBag()->first(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $keeper
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $keeper = User::where('id', Crypt::decrypt($id))->first();
        $last_block = Block::where([
            ['blocked_id', '=', $keeper->id],
            ['position', '=', 'keeper'],
        ])->orderBy('created_at', 'DESC')->first();
        //
        return response()->json([
            'keeper' => $keeper,
            'last_block' => $last_block,
        ], Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $keeper
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $keeper = User::findOrFail(Crypt::decrypt($id));
        //
        return response()->view('backend.keepers.update', [
            'keeper' => $keeper
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $keeper
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $keeper = User::findOrFail(Crypt::decrypt($id));
        $validator = Validator($request->only([
            'fname',
            'sname',
            'tname',
            'lname',
            'phone',
            'email',
            'gender',
            'status',
            'identity_no',
            'password',
            'image',
            'local_region',
            'description'
        ]), [
            'fname' => 'required|string|min:2|max:20',
            'sname' => 'required|string|min:2|max:20',
            'tname' => 'required|string|min:2|max:20',
            'lname' => 'required|string|min:2|max:20',
            'phone' => 'required|string|min:7|max:13|unique:users,phone,' . $keeper->id,
            'email' => 'required|email|unique:users,email,' . $keeper->id,
            'gender' => 'required|string|in:male,female',
            'status' => 'required|string|in:active,draft,blocked',
            'identity_no' => 'required|string|min:9|max:9|unique:users,identity_no,' . $keeper->id,
            'password' => ['nullable', Password::min(8)->uncompromised()->letters()->numbers(), 'max:25'],
            'image' => 'nullable',
            'local_region' => 'nullable|min:5|max:50',
            'description' => 'nullable|min:10|max:150',
        ]);
        //
        if (!$validator->fails()) {
            $keeper->fname = $request->post('fname');
            $keeper->sname = $request->post('sname');
            $keeper->tname = $request->post('tname');
            $keeper->lname = $request->post('lname');
            $keeper->phone = $request->post('phone');
            $keeper->identity_no = $request->post('identity_no');
            $keeper->email = $request->post('email');
            if ($request->post('password')) {
                $keeper->password = Hash::make($request->post('password'));
            }
            $keeper->gender = $request->post('gender');
            $keeper->status = $request->post('status');
            $keeper->local_region = $request->post('local_region');
            $keeper->description = $request->post('description');
            $image_path = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $image_path = $file->store('user', 'public');
                $keeper->image = $image_path;
            }
            $isUpdated = $keeper->save();

            return response()->json([
                'message' => $isUpdated
                    ? 'keeper updated successfully.'
                    : 'Failed to update keeper, please try again!'
            ], $isUpdated
                ? Response::HTTP_OK
                : Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'message' => $validator->getMessageBag()->first(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $keeper
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $keeper = User::findOrFail(Crypt::decrypt($id));
        //
        if ($keeper->delete()) {
            return response()->json([
                'title' => 'Deleted',
                'text' => 'keeper deleted successfully.',
                'icon' => 'success',
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'title' => 'Failed!',
                'text' => 'Failed to delete keeper, please try again!',
                'icon' => 'error',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
