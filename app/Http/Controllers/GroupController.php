<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
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
    public function getReportSpecificBranch($id)
    {
        // $manager = Manager::findOrFail(Crypt::decrypt($id));
        // $manager = Manager::find(Crypt::decrypt($id));
        return Excel::download(new Group(Crypt::decrypt($id)), 'manager.xlsx');
    }
}
