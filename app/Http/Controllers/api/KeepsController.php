<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class KeepsController extends Controller
{
    //
    public function index () {
        $user = Auth::user();
        return response()->json([
            'keeps'=> $user->keeps()->paginate(),
        ], Response::HTTP_OK);
    }
}
