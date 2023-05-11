<?php

namespace App\Http\Controllers\api\reports;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    //
    // Get a report about group
    public function getGroupStudent($group_id = null)
    {
        if ($group_id) {

            if (auth()->user()->position !== 'parent' && auth()->user()->position !== 'student' && \auth()->user()->position !== 'keeper') {
                return response()->json([
                    'group' => Group::where('id', $group_id)->with('students')->withCount('students')->with('center', function ($query) {
                        $query->with('branch', function ($query) {
                            $query->with('supervisor');
                        });
                    })->get(),
                ], Response::HTTP_OK);
            } else {
                return \response()->json([
                    'message' => 'Unauthorized!',
                ], Response::HTTP_BAD_REQUEST);
            }
        } else {
            $user = Auth::user();

            if ($user->position === 'keeper') {
                return \response()->json([
                    'group' => $user->group()->with('students')->withCount('students')->with('center', function ($query) {
                        $query->with('branch', function ($query) {
                            $query->with('supervisor');
                        });
                    })->get(),
                ]);
            } else {
                return \response()->json([
                    'message' => 'Unauthorized!',
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }

    // Get a report about student keeps
    public function getStudentKeepReport ($student_id) {
        $keeps = DB::table('keeps')->where('student_id', $student_id)->get();
        return \response()->json([
            'keeps' => $keeps,
            'student' => User::where('id', $student_id)->first(),
        ]);
    }
}
