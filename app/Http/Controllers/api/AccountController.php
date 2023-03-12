<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\ChangeStudentParentPassword;
use App\Http\Requests\api\UpdateStudentParentAccountRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    //

    public function getAccoountInformation () {
        return response()->json([
            'account' => User::where([
                ['id', '=', auth()->user()->id],
                ['position', '=', auth()->user()->position]
            ])->first(),
        ], Response::HTTP_OK);
    }

    public function update (UpdateStudentParentAccountRequest $request) {
        $user = Auth::user();
        $user->fname = $request->input('fname');
        $user->sname = $request->input('sname');
        $user->tname = $request->input('tname');
        $user->lname = $request->input('lname');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');
        $user->gender = $request->input('gender');
        $user->local_region = $request->input('local_region') ?? null;
        $user->description = $request->input('description') ?? null;
        $image_path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image_path = $file->store('user/admins', 'public');
            $user->image = $image_path;
        }
        $isUpdated = $user->save();

        return \response()->json([
            'message' => $isUpdated ? 'Account updated successfully' : 'Failed to update your account',
        ], $isUpdated ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

    public function changePassword (Request $request) {
        $user = Auth::user();

        if ($request->post('new_password') !== $request->post('confirmation_password')) {
            return \response()->json([
                'message' => 'Confirmation password does not match!'
            ], Response::HTTP_BAD_REQUEST);
        }

        $isChanged = false;
        if (Hash::check($request->post('current_password'), $user->password)) {
            $isChanged = User::where([
                ['id', '=', $user->id],
                ['position', '=', $user->position],
            ])->update([
                'password' => Hash::make($request->post('new_password'))
            ]);

        }else {
            return \response()->json([
                'message' => 'Something went wrong, please try again!',
            ], Response::HTTP_BAD_REQUEST);
        }

        return \response()->json([
            'message' => $isChanged ? 'Password changed successfully' : 'Failed to change password',
        ], $isChanged ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }
}
