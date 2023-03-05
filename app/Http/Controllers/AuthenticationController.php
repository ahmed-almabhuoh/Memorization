<?php

namespace App\Http\Controllers;

use App\Events\AuthenticatedDraftUserEvent;
use App\Listeners\AuthenticatedDraftUserListener;
use App\Models\Block;
use App\Models\User;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationController extends Controller
{
    //

    public function showLogin($guard = 'web')
    {
        return response()->view('auth.login', [
            'guard' => $guard,
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator($request->only([
            'username',
            'password',
            'guard',
        ]), [
            'username' => 'required|string',
            'password' => 'required|string',
            'guard' => 'required|string|in:web',
        ], [
            'guard.in' => 'Wrong URL, please try again!',
        ]);
        //
        if (!$validator->fails()) {
            $key = is_numeric($request->post('username')) ? 'identity_no' : 'email';
            $credintials = [
                $key => $request->post('username'),
                'password' => $request->post('password')
            ];



            // Get User
            $user = null;
            $column_name = is_numeric($request->post('username')) ? 'identity_no' : 'email';
            $user = User::where($column_name, $request->post('username'))->first();

//            return \response()->json([
//                'message' => 'Here'
//            ], 400);

            if (is_null($user)) {
                return response()->json([
                    'message' => 'Wrong credentials, please try again!',
                ], Response::HTTP_BAD_REQUEST);
            }

            if ($user->status === 'blocked' && Hash::check($credintials['password'], $user->password)) {
                // Check user block function
                return $this->isBlocked($credintials, $key === 'email' ? true : false, $request->post('guard'), $user);
            }

            if ($user->deleted_at) {
                return $this->isSoftDeleted($user);
            }

            if (Auth::guard($request->post('guard'))->attempt($credintials, false)) {
                event(new AuthenticatedDraftUserEvent(Auth::guard($request->post('guard'))->user()));
                return response()->json([
                    'message' => 'Login successfully',
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'Failed to login, please try again!',
                ], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json([
                'message' => $validator->getMessageBag()->first(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function logout(Request $request)
    {
        $guard = 'web';

//        if (auth('web')->check()) {
//            $guard = 'admin';
//        }

        $request->session()->invalidate();
        auth($guard)->logout();
        return redirect()->route('login', $guard);
    }

    // Check Blocked Users
    public function isBlocked($credintials, $is_email = false, $guard = 'manager', $user)
    {
        // Check user authenticate credentials
        if (Hash::check($credintials['password'], $user->password)) {
            if ($user->status === 'blocked') {

                // Last block
                $last_block = $user->blocks;

                if (!empty($last_block)) {
                    return response()->json([
                        'message' => 'Your account has been blocked: ' . $last_block[0]->description,
                    ], Response::HTTP_BAD_REQUEST);
                }
            }
        }
    }

    // Deny SoftDeleted
    public function isSoftDeleted($user)
    {
        if ($user->deleted_at) {
            return response()->json([
                'message' => 'You cannot access to your account at this moment!',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
