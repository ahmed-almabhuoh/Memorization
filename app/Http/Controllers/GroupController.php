<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Keeper;
use App\Models\Center;
use App\Models\Group;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = Group::paginate();
        //
        return response()->view('backend.groups.index', [
            'groups' => $groups,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->view('backend.groups.store', [
            'centers' => Center::active()->get(),
            'keepers' => User::keeperWithoutGroup()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator($request->only([
            'name',
            'status',
            'region',
            'image',
            'center_id',
            'keeper_id',
        ]), [
            'name' => 'required|string|min:2|max:25|unique:groups,name',
            'status' => 'required|string|in:' . implode(',', Group::STATUS),
            'region' => 'nullable|min:5|max:50',
            'image' => 'nullable',
            'center_id' => 'required|integer|exists:centers,id',
            'keeper_id' => 'required|integer|exists:users,id',
        ]);
        //
        if (!$validator->fails()) {
            $group = new Group();
            $group->name = $request->post('name');
            $group->status = $request->post('status');
            $group->region = $request->post('region');
            $group->center_id = $request->post('center_id');
            $group->keeper_id = $request->post('keeper_id');
            $image_path = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $image_path = $file->store('content/groups', 'public');
            }
            $group->image = $image_path;
            $isCreated = $group->save();


            return response()->json([
                'message' => $isCreated
                    ? 'Group added successfully.'
                    : 'Failed to add group, please try again!'
            ], $isCreated
                ? Response::HTTP_CREATED
                : Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'message' => $validator->getMessageBag()->first(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $group = Group::findOrFail(Crypt::decrypt($id));
        //
        return response()->view('backend.groups.update', [
            'group' => $group,
            'centers' => Center::active()->get(),
            // scopeKeeperOwnWithoutGroup
            'keepers' => User::keeperOwnWithoutGroup()->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $group = Group::findOrFail(Crypt::decrypt($id));
        $validator = Validator($request->only([
            'name',
            'status',
            'region',
            'image',
            'center_id',
            'keeper_id',
        ]), [
            'name' => 'required|string|min:2|max:25|unique:groups,name,' . $group->id,
            'status' => 'required|string|in:' . implode(',', Group::STATUS),
            'region' => 'nullable|min:5|max:50',
            'image' => 'nullable',
            'center_id' => 'required|string|exists:centers,id',
            'keeper_id' => 'required|string|exists:users,id',
        ]);
        //
        if (!$validator->fails()) {
            $group->name = $request->post('name');
            $group->status = $request->post('status');
            $group->region = $request->post('region');
            $group->center_id = $request->post('center_id');
            $group->keeper_id = $request->post('keeper_id');
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $image_path = $file->store('content/groups', 'public');
                $group->image = $image_path;
            }
            $isUpdated = $group->save();


            return response()->json([
                'message' => $isUpdated
                    ? 'Group updated successfully.'
                    : 'Failed to update group, please try again!'
            ], $isUpdated
                ? Response::HTTP_CREATED
                : Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'message' => $validator->getMessageBag()->first(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $group = Group::findOrFail(Crypt::decrypt($id));
        //
        if ($group->delete()) {
            return response()->json([
                'title' => 'Deleted',
                'text' => 'Group deleted successfully.',
                'icon' => 'success',
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'title' => 'Failed!',
                'text' => 'Failed to delete group, please try again!',
                'icon' => 'error',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    // Get groups report
    public function getReport()
    {
        return Excel::download(new Group(), 'groups.xlsx');
    }

    // Get manager report
    public function getReportSpecificGroup($id)
    {
        return Excel::download(new Group(Crypt::decrypt($id)), 'group.xlsx');
    }

    // View all students
    public function viewAddStudent ($group_id) {
        $group = Group::where('id', Crypt::decrypt($group_id))->first();

        return \response()->view('backend.groups.add-students', [
            'group' =>  $group,
            'group_students' =>  $group->students,
            'students' =>  User::students()->get(),
        ]);
    }

//    Add Student To Group
    public function addStudentrToGroup ($group_id, $student_id) {
        $group  = Group::where('id', Crypt::decrypt($group_id))->first();
        $student  = User::students()->where('id', Crypt::decrypt($student_id))->first();

        /* Check Avilability*/
        if (is_null($group) || is_null($student)) {
            return \response()->json([
                'message' => 'Wrong URL, please try again!',
            ], Response::HTTP_BAD_REQUEST);
        }else {
            if (!DB::table('group_student')->where([
                ['student_id', '=', $student->id],
                ['group_id', '=', $group->id],
            ])->exists()) {
                DB::table('group_student')->insert([
                    'student_id' => $student->id,
                    'group_id' => $group->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                return \response()->json([
                    'message'=> 'Student ' . $student->fname . ' add successfully to the ' . $group->name . ' group.',
                ], Response::HTTP_CREATED);
            }else {
                DB::table('group_student')->where([
                    ['student_id', '=', $student->id],
                    ['group_id', '=', $group->id],
                ])->delete();

                return \response()->json([
                    'message'=> 'Student ' . $student->fname . ' removed successfully from ' . $group->name . ' group.',
                ], Response::HTTP_OK);
            }
        }
    }
}
