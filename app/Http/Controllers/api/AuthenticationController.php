<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\LoginRequest;
//use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationController extends Controller
{
    //
    public function login (LoginRequest $request) {
        //
//        $key = is_numeric($request->post('username')) ? 'identity_no' : 'email';
        $credentials = [
            'identity_no' => $request->post('username'),
            'password' => $request->post('password'),
        ];


        if (Auth::guard('web')->attempt($credentials, $request->post('remember_me'))) {
            $user = Auth::user();
            if ($user->deleted_at) {
                return redirect()->route('logout');
            }
//            Delete all tokens for the user
            $user->tokens()->delete();

            $token = $user->createToken('Memorization Quran')->plainTextToken;
            return response()->json([
                '_token' => $token,
                'user' => $user,
            ], Response::HTTP_CREATED);
        }else {
            return \response()->json([
                'message' => 'Wrong credentials, please try again later!',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function logout () {
        $user = Auth::user();
        $user->tokens()->delete();
        return response()->json([
            'message' => 'Successfully logged out',
        ], Response::HTTP_OK);
    }
}
