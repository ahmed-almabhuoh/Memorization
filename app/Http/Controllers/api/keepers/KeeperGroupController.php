<?php

namespace App\Http\Controllers\api\keepers;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\students\StoreNewStudentRequest;
use App\Models\Group;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class KeeperGroupController extends Controller
{
    //

    /*
     * Get Keeper Group
     * */
    public function getGroups()
    {
        return response()->json([
            'groups' => auth()->user()->group()->paginate(),
        ], Response::HTTP_OK);
    }

    public function getGroupStudents($group_id = null)
    {
        if ($group_id) {
            $group = Group::whereHas('keeper', function ($query) {
                $query->where('id', auth()->user()->id);
            })->where('id', $group_id)->first();

            return \response()->json([
                'group' => $group,
                'students' => $group->students,
            ], Response::HTTP_OK);
        } else {
            $groups = Group::whereHas('keeper', function ($query) {
                $query->where('id', auth()->user()->id);
            })->get();

            return \response()->json([
                'groups' => $groups,
            ], Response::HTTP_OK);
        }
    }


    // Add a new student to my group
    public function addStudent(Request $request)
    {


        $request->validate([
            'student_id' => 'required|integer|exists:users,id',
        ]);
        //
        $student = User::students()->where('id', $request->post('student_id'))->first();
        $keeper = Auth::user();
        $group = $keeper->group;

        if (DB::table('group_student')->where([
                ['group_id', '=', $group->id],
            ])->count() >= 20) {
            return \response()->json([
                'message' => 'The group contains 20 students, and this is the maximum number can assigned to this group!',
            ], Response::HTTP_BAD_REQUEST);
        } else {
            if (is_null($student) || is_null($keeper)) {

                return \response()->json([
                    'message' => 'Student you requested or your account not found, please try again!',
                ], Response::HTTP_BAD_REQUEST);

            } else {
                if (!is_null($group)) {

                    if (!DB::table('group_student')->where([
                        ['group_id', '=', $group->id],
                        ['student_id', '=', $student->id],
                    ])->exists()) {

                        $id = DB::table('group_student')->insertGetId([
                            'group_id' => $group->id,
                            'student_id' => $student->id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                        return \response()->json([
                            'message' => $id ? 'Student ( ' . $student->fname . ') add to group.' : 'Failed to add the student to your group!',
                        ], $id ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);

                    } else {
                        return \response()->json([
                            'message' => 'Student you are trying to add to this group already exists!',
                        ], Response::HTTP_BAD_REQUEST);
                    }


                } else {
                    return \response()->json([
                        'message' => 'You do not have a group yet, contact with support to add a group for you',
                    ], Response::HTTP_BAD_REQUEST);
                }
            }
        }
    }


    // Remove Student from group
    public function removeStudent(Request $request)
    {
        $request->validate([
            'student_id' => 'required|integer|exists:users,id',
        ]);
        //
        $student = User::students()->where('id', $request->post('student_id'))->first();
        $keeper = Auth::user();
        $group = $keeper->group;


        if (is_null($student) || is_null($keeper) || is_null($group)) {
            return \response()->json([
                'message' => 'Something went wrong with your request, please try again later!',
            ], Response::HTTP_BAD_REQUEST);
        } else {
            if (DB::table('group_student')->where([
                ['group_id', '=', $group->id],
                ['student_id', '=', $student->id],
            ])->exists()) {
                DB::table('group_student')->where([
                    ['group_id', '=', $group->id],
                    ['student_id', '=', $student->id],
                ])->delete();

                return \response()->json([
                    'message' => 'Student removed successfully',
                ], Response::HTTP_OK);
            } else {
                return \response()->json([
                    'message' => 'The student does not exists in this group to be removed!',
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }

    // Get my students in my group
    public function getStudents()
    {
        $user = Auth::user();
        $group = $user->group;

        return \response()->json([
            'students' => $group->students,
            'group' => $group,
        ]);
    }

    // Add new student with normal process
    public function addStudentNormalProcess(StoreNewStudentRequest $request)
    {
        $student = new User();
        $student->fname = $request->input('fname');
        $student->sname = $request->input('sname');
        $student->tname = $request->input('tname');
        $student->lname = $request->input('lname');
        $student->phone = $request->input('phone');
        $student->identity_no = $request->input('identity_no');
        $student->email = $request->input('email');
        $student->position = $request->post('position');
        $student->password = Hash::make($request->input('password'));
        $student->gender = $request->input('gender');
        $student->status = $request->input('status');
        $student->local_region = $request->input('local_region');
        $student->description = $request->input('description');
        $image_path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image_path = $file->store('user/students', 'public');
        }
        $student->image = $image_path;
        $isCreated = $student->save();

        $user = Auth::user();
        $group = $user->group;

        if ($isCreated) {
            $id = DB::table('group_student')->insertGetId([
                'group_id' => $group->id,
                'student_id' => $student->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return \response()->json([
                'message' => $id ? 'Student ( ' . $student->fname . ') add to group.' : 'Failed to add the student to your group!',
            ], $id ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);
        } else {
            return \response()->json([
                'message' => 'Something went wrong, please try again later!'
            ], Response::HTTP_BAD_REQUEST);
        }

    }
}
