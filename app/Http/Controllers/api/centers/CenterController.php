<?php

namespace App\Http\Controllers\api\centers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Center;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json([
            'centers' => Center::get(),
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:50|unique:centers,name',
            'branch_id' => 'required|integer|exists:branches,id',
            'status' => 'required|string|in:' . implode(",", Center::STATUS),
            'image' => 'nullable',
            'local_region' => 'nullable',
        ]);
        //
        $center = new Center();
        $center->name = $request->post('name');
        $center->branch_id = $request->post('branch_id');
        $center->status = $request->post('status');
        $center->region = $request->post('local_region');
        $image_path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image_path = $file->store('content/centers', 'public');
        }
        $center->image = $image_path;

        $isCreated = $center->save();

        return \response()->json([
            'message' => $isCreated ? 'Center added successfully' : 'Failed to add a new center, please try again!',
        ], $isCreated ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $center = Center::where('id', $id)->first();
        if (is_null($center)) {
            return \response()->json([
                'message' => 'Center not found!',
            ], 400);
        }else {
            return \response()->json([
                'center' => $center,
            ], 200);
        }
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $center = Center::where('id', $id)->first();
        $request->validate([
            'name' => 'required|string|min:2|max:50|unique:centers,name,' . $center->id,
            'branch_id' => 'required|integer|exists:branches,id',
            'status' => 'required|string|in:' . implode(",", Center::STATUS),
            'image' => 'nullable',
            'local_region' => 'nullable',
        ]);
        //
        $center->name = $request->post('name');
        $center->branch_id = $request->post('branch_id');
        $center->status = $request->post('status');
        $center->region = $request->post('local_region');
        $image_path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image_path = $file->store('content/centers', 'public');
        }
        $center->image = $image_path;

        $isUpdated = $center->save();

        return \response()->json([
            'message' => $isUpdated ? 'Center updated successfully' : 'Failed to update the center, please try again!',
        ], $isUpdated ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $center = Center::where('id', $id)->first();
        //
        if ($center->delete()) {
            return \response()->json([
                'message' => 'Center (' . $center->name . ') deleted successfully',
            ], Response::HTTP_OK);
        }else {
            return \response()->json([
                'message' => 'Failed to delete the center, please try again later!',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
