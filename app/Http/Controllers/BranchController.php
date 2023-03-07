<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branches = Branch::with('supervisor')->paginate();
        //
        return response()->view('backend.branches.index', [
            'branches' => $branches,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return response()->view('backend.branches.store');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator($request->only([
            'name',
            'status',
            'region',
            'image'
        ]), [
            'name' => 'required|string|min:2|max:25|unique:branches,name',
            'status' => 'required|string|in:' . implode(',', Branch::STATUS),
            'region' => 'nullable|min:5|max:50',
            'image' => 'nullable',
        ]);
        //
        if (!$validator->fails()) {
            $branch = new Branch();
            $branch->name = $request->post('name');
            $branch->status = $request->post('status');
            $branch->region = $request->post('region');
            $image_path = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $image_path = $file->store('content/branches', 'public');
            }
            $branch->image = $image_path;
            $isCreated = $branch->save();


            return response()->json([
                'message' => $isCreated
                    ? 'Branch added successfully.'
                    : 'Failed to add branch, please try again!'
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
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $branch = Branch::findOrFail(Crypt::decrypt($id));
        //
        return response()->view('backend.branches.update', [
            'branch' => $branch
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail(Crypt::decrypt($id));
        $validator = Validator($request->only([
            'name',
            'status',
            'region',
            'image'
        ]), [
            'name' => 'required|string|min:2|max:25|unique:branches,name,' . $branch->id,
            'status' => 'required|string|in:' . implode(',', Branch::STATUS),
            'region' => 'nullable|min:5|max:50',
            'image' => 'nullable',
        ]);
        //
        if (!$validator->fails()) {
            $branch->name = $request->post('name');
            $branch->status = $request->post('status');
            $branch->region = $request->post('region');
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $image_path = $file->store('content/branches', 'public');
                $branch->image = $image_path;
            }
            $isUpdated = $branch->save();


            return response()->json([
                'message' => $isUpdated
                    ? 'Branch updated successfully.'
                    : 'Failed to update branch, please try again!'
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
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $branch = Branch::findOrFail(Crypt::decrypt($id));
        //
        if ($branch->delete()) {
            return response()->json([
                'title' => 'Deleted',
                'text' => 'Branch deleted successfully.',
                'icon' => 'success',
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'title' => 'Failed!',
                'text' => 'Failed to delete branch, please try again!',
                'icon' => 'error',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    // Get branches report
    public function getReport()
    {
        return Excel::download(new Branch(), 'branches.xlsx');
    }

    // Get manager report
    public function getReportSpecificBranch($id)
    {
        // $manager = Manager::findOrFail(Crypt::decrypt($id));
        // $manager = Manager::find(Crypt::decrypt($id));
        return Excel::download(new Branch(Crypt::decrypt($id)), 'manager.xlsx');
    }

    // Assign CEO For Branch
    public function showAddUser($branch_id)
    {
        $branch = Branch::findOrFail(Crypt::decrypt($branch_id));

        return response()->view('backend.branches.CEOs', [
            'branch' => $branch,
            'supervisors' => User::whereDoesntHave('sc')
                ->whereDoesntHave('branch', function ($query) use ($branch) {
                    $query->where('id', '!=', $branch->id);
                })
                ->where('position', 'supervisor')
                ->get(),
        ]);
    }

    // Add CEO To Branch
    public function addUserToBranch($branch_id, $s_id)
    {
        $branch = Branch::find(Crypt::decrypt($branch_id));
        $user = User::find(Crypt::decrypt($s_id));

        if (is_null($branch) || is_null($user)) {
            return response()->json([
                'message' => 'Wrong URL/URI!',
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($branch->supervisor_id && $branch->supervisor_id !== $user->id) {
            return response()->json([
                'message' => 'This branch leads by ' . User::find($branch->id) ?? ' Not Defined ' . ' at this moment!'
            ], Response::HTTP_BAD_REQUEST);
        } else {
            $success_MSG = $user->id === $branch->supervisor_id ? $user->full_name . ' removed sucessfullt from' . $branch->name . ' branch.' : $user->full_name . ' added sucessfullt as a CEO to ' . $branch->name . ' branch.';

            $branch->supervisor_id = $user->id === $branch->supervisor_id ? null : $user->id;
            $isAdded = $branch->save();

            return response()->json([
                'message' => $isAdded ? $success_MSG : 'Failed to add supervisor to this branch, please try again!',
            ], $isAdded ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
        }
    }
}
