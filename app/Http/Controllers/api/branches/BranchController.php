<?php

namespace App\Http\Controllers\api\branches;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json([
            'branches' => Branch::get(),
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:50|unique:branches,name',
            'status' => 'required|string|in:' . implode(",", Branch::STATUS),
            'image' => 'nullable',
            'local_region' => 'nullable|min:10|max:100',
        ]);
        //
        $branch = new Branch();
        $branch->name = $request->post('name');
        $branch->status = $request->post('status');
        $image_path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image_path = $file->store('content/branches', 'public');
        }
        $branch->image = $image_path;
        $branch->region = $request->post('local_region');

        $isCreated = $branch->save();

        return \response()->json([
            'message' => $isCreated ? 'Branch added successfully' : 'Failed to add the branch, please try again!',
        ], $isCreated ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $branch = Branch::where('id', $id)->first();

        if (is_null($branch)) {
            return \response()->json([
                'message' => 'Branch not found!',
            ], 404);
        } else {
            return \response()->json([
                'branch' => $branch,
            ], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $branch = Branch::where('id', $id)->first();
        if (is_null($branch)) {
            return \response()->json([
                'message' => 'Branch not found!',
            ], 404);
        }
        $request->validate([
            'name' => 'required|string|min:2|max:50|unique:branches,name,' . $branch->id,
            'status' => 'required|string|in:' . implode(",", Branch::STATUS),
            'image' => 'nullable',
            'local_region' => 'nullable|min:10|max:100',
        ]);
        //
        $branch->name = $request->post('name');
        $branch->status = $request->post('status');
        $image_path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image_path = $file->store('content/branches', 'public');
        }
        $branch->image = $image_path;
        $branch->region = $request->post('local_region');

        $isUpdated = $branch->save();

        return \response()->json([
            'message' => $isUpdated ? 'Branch updated successfully' : 'Failed to update the branch, please try again!',
        ], $isUpdated ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $branch = Branch::where('id', $id)->first();
        //
        if ($branch->delete()) {
            return \response()->json([
                'message' => 'Branch (' . $branch->name . ') deleted successfully',
            ], Response::HTTP_OK);
        }else {
            return \response()->json([
                'message' => 'Failed to delete the branch, please try again later!',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
