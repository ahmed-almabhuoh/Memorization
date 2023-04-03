<?php

namespace App\Http\Controllers\api\accounts;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\accounts\ChangeMyPasswordRequest;
use App\Http\Requests\api\accounts\UpdateMyAccountRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    //
    public function getAccount()
    {
        return auth()->user();
    }

    public function updateMyAccount(UpdateMyAccountRequest $request)
    {
        $user = User::where('id', auth()->user()->id)->first();

//        return \response()->json([
//            'message' => User::where([
//                ['email', '=', $request->post('email')],
//                ['id', '!=', $user->id],
//            ])->exists()
//        ]);
//
//        if (User::where([
//                ['email', '=', $request->post('email')],
//                ['id', '!=', $user->id],
//            ])->exists() || User::where([
//                ['identity_no', '=', $request->post('identity_no')],
//                ['id', '!=', $user->id],
//            ])->exists()) {
//            return \response()->json([
//                'message' => 'Some data duplicated!',
//            ]);
//        }

        $user->fname = $request->post('fname');
        $user->sname = $request->post('sname');
        $user->tname = $request->post('tname');
        $user->lname = $request->post('lname');
        $user->phone = $request->post('phone');
        $user->email = $request->post('email');
        $user->identity_no = $request->post('identity_no');
        $user->local_region = $request->post('local_region');
        $user->description = $request->post('description');
        $image_path = $user->image;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image_path = $file->store('user', 'public');
            $user->image = $image_path;
        }
        $isUpdated = $user->save();


        return response()->json([
            'message' => $isUpdated ? 'Account updated' : 'Failed to update, please try again!',
            'user' => auth()->user(),
        ], $isUpdated ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

    public function changeMyPassword(ChangeMyPasswordRequest $request)
    {
        $user = User::where('id', auth()->user()->id)->first();

        $user->password = Hash::make($request->post('password'));
        $isChanged = $user->save();

        return \response()->json([
            'message' => $isChanged ? 'Password changed' : 'Failed to change password!',
        ], $isChanged ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

    public function deleteMyAccount()
    {
        $user = User::where('id', auth()->user()->id)->first();
        $user->status = 'draft';
        $user->deleted_at = Carbon::now();
        $user->save();

        return redirect()->route('logout');
    }
}
