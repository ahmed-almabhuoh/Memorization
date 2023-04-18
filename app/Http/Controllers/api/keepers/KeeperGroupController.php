<?php

namespace App\Http\Controllers\api\keepers;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
