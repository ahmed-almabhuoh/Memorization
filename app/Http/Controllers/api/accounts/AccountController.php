<?php

namespace App\Http\Controllers\api\accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    //
    public function getAccount () {
        return auth()->user();
    }
}
