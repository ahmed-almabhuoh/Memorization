<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AbsenceController extends Controller
{
    //
    public function setKeeperController () {
        if (auth()->user()->position !== 'keeper')
            return response()->json([
                'message' => 'Unavilable resources',
            ], 403);

        $user = Auth::user();
        DB::table('absences')->orderBy('created_at', 'DESC')->where([
            ['absence_date', '=', now()->toDateString()],
            ['user_id', '=', $user->id],
            ['status', '=', false],
            ['absence_type', '=', 'keeper'],
        ])->update([
            'status' => true,
        ]);

        return response()->json([
            'message' => 'Attendance has been registered successfully, before logout you should your submit date to confirm your attendance',
        ]);
    }

    public function submitReport (Request $request) {
        $request->validate([
            'report' => 'required|string|min:5',
        ]);
        //
        $user = Auth::user();
        DB::table('absences')->orderBy('created_at', 'DESC')->where([
            ['absence_date', '=', now()->toDateString()],
            ['user_id', '=', $user->id],
            ['status', '=', true],
            ['report', '=', null],
            ['absence_type', '=', 'keeper'],
        ])->update([
            'report' => $request->post('report'),
        ]);

        return response()->json([
            'message' => 'Report submitted successfully, your are logged out.',
        ]);
    }

    /*
     * Get Student Today Attendance
     * */
    public function getStudent () {
        $keeper = Auth::user();

        if ($keeper->position !== 'keeper')
                return response()->json([
                    'message' => 'Unavilable resources!',
                ], 400);

        $keeper_students = $keeper->group->students;
        foreach ($keeper_students as $student) {
            $student->setAttribute('is_attendance', false);
            if ((DB::table('absences')->where([
                ['user_id', '=', $student->id],
                ['absence_type', '=', 'student'],
                ['absence_date', '=', now()->toDateString()]
            ])->first())->status) {
                $student->setAttribute('is_attendance', true);
            }
        }

        return response()->json([
            'group' => $keeper->group,
            'students' => $keeper_students,
        ], Response::HTTP_OK);
    }

    /*
     * Record Student Attendance
     * */
    public function submitStudentAttendance (Request $request) {
        $request->validate([
            'student_id' => 'required|integer|exists:users,id',
            'is_attendance' => 'required|boolean',
            'notes' => 'nullable',
        ]);
        //
        $keeper = Auth::user();
        $student_id = $request->post('student_id');

        /*
         * Is the students belong to the authenticated keeper via group or not ?!
         * */
        if ($keeper->group()->whereHas('students', function ($query) use ($student_id) {
            $query->where('users.id', '=', $student_id);
        })->exists()) {

            DB::table('absences')->where([
                ['user_id', '=', $student_id],
                ['absence_type', '=', 'student'],
                ['absence_date', '=', now()->toDateString()]
            ])->update([
                'status' => $request->post('is_attendance'),
                'report' => $request->post('notes'),
                'updated_at' => Carbon::now(),
            ]);

            return \response()->json([
                'message' => 'Student attendance saved successfully',
            ], Response::HTTP_OK);

        }else {
            return \response()->json([
                'message' => 'Something went wrong!'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
