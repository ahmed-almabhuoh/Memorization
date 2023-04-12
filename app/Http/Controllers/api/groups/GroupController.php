<?php

namespace App\Http\Controllers\api\groups;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json([
            'groups' => Group::get(),
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'required|string|in:' . implode(",", Group::STATUS),
            'center_id' => 'required|integer|exists:centers,id',
            'keeper_id' => 'required|integer|exists:users,id',
            'image' => 'nullable',
            'local_region' => 'nullable',
        ]);
        //
        $group = new Group();
        $group->name = $request->post('name');
        $group->status = $request->post('status');
        $group->center_id = $request->post('center_id');
        $group->keeper_id = $request->post('keeper_id');
        $group->region = $request->post('local_region');
        $image_path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image_path = $file->store('content/groups', 'public');
        }
        $group->image = $image_path;
        $isCreated = $group->save();

        return \response()->json([
            'message' => $isCreated ? 'Group added successfully' : 'Failed to add a group, please try again!',
        ], $isCreated ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $group = Group::where('id', $id)->first();
        if (is_null($group)) {
            return \response()->json([
                'message' => 'Group not found!',
            ], 400);
        } else {
            return \response()->json([
                'group' => $group,
            ], Response::HTTP_OK);
        }
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $group = Group::where('id', $id)->first();
        $request->validate([
            'name' => 'required|string',
            'status' => 'required|string|in:' . implode(",", Group::STATUS),
            'center_id' => 'required|integer|exists:centers,id',
            'keeper_id' => 'required|integer|exists:users,id',
            'image' => 'nullable',
            'local_region' => 'nullable',
        ]);
        //
        $group->name = $request->post('name');
        $group->status = $request->post('status');
        $group->center_id = $request->post('center_id');
        $group->keeper_id = $request->post('keeper_id');
        $group->region = $request->post('local_region');
        $image_path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image_path = $file->store('content/groups', 'public');
        }
        $group->image = $image_path;
        $isUpdated = $group->save();

        return \response()->json([
            'message' => $isUpdated ? 'Group updated successfully' : 'Failed to update the group, please try again!',
        ], $isUpdated ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $group = Group::where('id', $id)->first();
        //
        if ($group->delete()) {
            return \response()->json([
                'message' => 'Group (' . $group->name . ') deleted successfully',
            ], Response::HTTP_OK);
        } else {
            return \response()->json([
                'message' => 'Failed to delete the group, please try again later!',
            ], Response::HTTP_BAD_REQUEST);
        }
    }


    public function addStudentToGroup(Request $request)
    {
        $request->validate([
            'student_id' => 'required|integer|exists:users,id',
            'group_id' => 'required|integer|exists:groups,id',
        ]);
        $student = User::where([
            ['id', '=', $request->post('student_id')],
            ['position', '=', 'student'],
        ])->first();
        $group = Group::where('id', $request->post('group_id'))->first();

        if (is_null($group) || is_null($student)) {
            return \response()->json([
                'message' => 'Wrong URL, please try again!',
            ], Response::HTTP_BAD_REQUEST);
        } else {
            $id = false;
            if (DB::table('group_student')->where([
                ['student_id', '=', $student->id],
                ['group_id', '=', $group->id],
            ])->exists()) {
                $id = DB::table('group_student')->where([
                    ['student_id', '=', $student->id],
                    ['group_id', '=', $group->id],
                ])->update([
                    'student_id' => $student->id,
                    'group_id' => $group->id,
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                $id = DB::table('group_student')->insertGetId([
                    'student_id' => $student->id,
                    'group_id' => $group->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            return \response()->json([
                'message' => $id ? 'Student (' . $student->fname . ') added to group (' . $group->name . ') successfully' : 'Failed to add the student to the group!',
            ], $id ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);
        }
    }
}
