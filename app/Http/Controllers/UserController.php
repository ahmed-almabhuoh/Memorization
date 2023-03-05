<?php

namespace App\Http\Controllers;

use App\Events\CreateBlockUserEvent;
use App\Models\Block;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    // Get users report
    public function getReport()
    {
        return Excel::download(new User(), 'users.xlsx');
    }

    // Get admin report
    public function getReportSpecificUser($id)
    {
        return Excel::download(new User(Crypt::decrypt($id)), 'user.xlsx');
    }

    // Get report from position
    public  function getReportSpecificPosition ($position = 'admin') {
        return Excel::download(new User(0, $position), 'users.xlsx');
//        dd($position);
    }
}
