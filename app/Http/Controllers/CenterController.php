<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Center;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class CenterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $centers = Center::with('branch')->paginate();
        //
        return response()->view('backend.centers.index', [
            'centers' => $centers,
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
        return response()->view('backend.centers.store', [
            'branches' => Branch::where('deleted_at', '=', null)->get(),
        ]);
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
            'image',
            'branch_id'
        ]), [
            'name' => 'required|string|min:2|max:25|unique:centers,name',
            'status' => 'required|string|in:' . implode(',', Center::STATUS),
            'region' => 'nullable|min:5|max:50',
            'branch_id' => 'required|integer|exists:branches,id',
            'image' => 'nullable',
        ], [
            'branch_id.exists' => 'Selected branch is not available!',
        ]);
        //
        if (!$validator->fails()) {
            $center = new Center();
            $center->name = $request->post('name');
            $center->status = $request->post('status');
            $center->region = $request->post('region');
            $center->branch_id = $request->post('branch_id');
            $image_path = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $image_path = $file->store('content/centers', 'public');
            }
            $center->image = $image_path;
            $isCreated = $center->save();


            return response()->json([
                'message' => $isCreated
                    ? 'Center added successfully.'
                    : 'Failed to add center, please try again!'
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
     * @param  \App\Models\Center  $center
     * @return \Illuminate\Http\Response
     */
    public function show(Center $center)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Center  $center
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $center = Center::findOrFail(Crypt::decrypt($id));
        //
        return response()->view('backend.centers.update', [
            'center' => $center,
            'branches' => Branch::where('deleted_at', '=', null)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Center  $center
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $center = Center::findOrFail(Crypt::decrypt($id));
        $validator = Validator($request->only([
            'name',
            'status',
            'region',
            'branch_id',
            'image'
        ]), [
            'name' => 'required|string|min:2|max:25|unique:centers,name,' . $center->id,
            'status' => 'required|string|in:' . implode(',', Center::STATUS),
            'region' => 'nullable|min:5|max:50',
            'branch_id' => 'required|integer|exists:branches,id',
            'image' => 'nullable',
        ], [
            'branch_id.exists' => 'Selected branch is not available!',
        ]);
        //
        if (!$validator->fails()) {
            $center->name = $request->post('name');
            $center->status = $request->post('status');
            $center->region = $request->post('region');
            $center->branch_id = $request->post('branch_id');
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $image_path = $file->store('content/centers', 'public');
                $center->image = $image_path;
            }
            $isUpdated = $center->save();


            return response()->json([
                'message' => $isUpdated
                    ? 'Center updated successfully.'
                    : 'Failed to update center, please try again!'
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
     * @param  \App\Models\Center  $center
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $center = Center::findOrFail(Crypt::decrypt($id));
        //
        if ($center->delete()) {
            return response()->json([
                'title' => 'Deleted',
                'text' => 'Center deleted successfully.',
                'icon' => 'success',
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'title' => 'Failed!',
                'text' => 'Failed to delete center, please try again!',
                'icon' => 'error',
            ], Response::HTTP_BAD_REQUEST);
        }
    }


    // Get centers report
    public function getReport()
    {
        return Excel::download(new Center(), 'centers.xlsx');
    }

    // Get manager report
    public function getReportSpecificCenter($id)
    {
        // $manager = Manager::findOrFail(Crypt::decrypt($id));
        // $manager = Manager::find(Crypt::decrypt($id));
        return Excel::download(new Center(Crypt::decrypt($id)), 'center.xlsx');
    }
}
