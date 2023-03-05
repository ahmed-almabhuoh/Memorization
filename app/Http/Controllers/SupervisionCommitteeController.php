<?php

namespace App\Http\Controllers;

use App\Models\SupervisionCommittee;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class SupervisionCommitteeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supervision_committees = SupervisionCommittee::paginate();
        //
        return response()->view('backend.supervision_committees.index', [
            'supervision_committees' => $supervision_committees,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('backend.supervision_committees.store');
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
            'type',
            'region',
            'image'
        ]), [
            'name' => 'required|string|min:2|max:25|unique:supervision_committees,name',
            'status' => 'required|string|in:' . implode(',', SupervisionCommittee::STATUS),
            'region' => 'nullable|min:5|max:50',
            'image' => 'nullable',
        ]);
        //
        if (!$validator->fails()) {
            $supervision_committee = new SupervisionCommittee();
            $supervision_committee->name = $request->post('name');
            $supervision_committee->status = $request->post('status');
            $supervision_committee->type = $request->post('type');
            $supervision_committee->region = $request->post('region');
            $image_path = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $image_path = $file->store('content/supervision_committees', 'public');
            }
            $supervision_committee->image = $image_path;
            $isCreated = $supervision_committee->save();


            return response()->json([
                'message' => $isCreated
                    ? 'Supervision Committee added successfully.'
                    : 'Failed to add supervision committee, please try again!'
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
     * @param  \App\Models\SupervisionCommittee  $supervisionCommittee
     * @return \Illuminate\Http\Response
     */
    public function show(SupervisionCommittee $supervisionCommittee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SupervisionCommittee  $supervisionCommittee
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $supervision_committee = SupervisionCommittee::findOrFail(Crypt::decrypt($id));
        //
        return response()->view('backend.supervision_committees.update', [
            'supervision_committee' => $supervision_committee
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SupervisionCommittee  $supervisionCommittee
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $supervision_committee = SupervisionCommittee::findOrFail(Crypt::decrypt($id));
        $validator = Validator($request->only([
            'name',
            'status',
            'type',
            'region',
            'image'
        ]), [
            'name' => 'required|string|min:2|max:25|unique:supervision_committees,name,' . $supervision_committee->id,
            'status' => 'required|string|in:' . implode(',', SupervisionCommittee::STATUS),
            'region' => 'nullable|min:5|max:50',
            'image' => 'nullable',
        ]);
        //
        if (!$validator->fails()) {
            $supervision_committee->name = $request->post('name');
            $supervision_committee->status = $request->post('status');
            $supervision_committee->type = $request->post('type');
            $supervision_committee->region = $request->post('region');
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $image_path = $file->store('content/supervision_committees', 'public');
                $supervision_committee->image = $image_path;
            }
            $isUpdated = $supervision_committee->save();


            return response()->json([
                'message' => $isUpdated
                    ? 'Supervision Committee updated successfully.'
                    : 'Failed to update supervision committee, please try again!'
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
     * @param  \App\Models\SupervisionCommittee  $supervisionCommittee
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $supervision_committee = SupervisionCommittee::findOrFail(Crypt::decrypt($id));
        //
        if ($supervision_committee->delete()) {
            return response()->json([
                'title' => 'Deleted',
                'text' => 'Supervision Committee deleted successfully.',
                'icon' => 'success',
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'title' => 'Failed!',
                'text' => 'Failed to delete supervision committee, please try again!',
                'icon' => 'error',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    // Get supervision committees report
    public function getReport()
    {
        return Excel::download(new SupervisionCommittee(), 'SupervisionCommittees.xlsx');
    }

    // Get manager report
    public function getReportSpecificSupervisionCommittee($id)
    {
        // $manager = Manager::findOrFail(Crypt::decrypt($id));
        // $manager = Manager::find(Crypt::decrypt($id));
        return Excel::download(new SupervisionCommittee(Crypt::decrypt($id)), 'SupervisionCommittee.xlsx');
    }

    // Assign Supervisor To SC View
    public function showAddSupverisors($id)
    {
        $sc = SupervisionCommittee::findOrFail(Crypt::decrypt($id));

        return response()->view('backend.supervision_committees.add-supervisors', [
            'sc' => $sc,
            'supervisors' => User::whereDoesntHave('branch')
                ->whereDoesntHave('sc', function ($query) use ($id) {
                    $query->where('id', '!=', $id);
                })->get(),
        ]);
    }

    // Assign Supervisor To SC
    public function addSupervisorToSC($sc_id, $s_id)
    {
        $sc = SupervisionCommittee::find(Crypt::decrypt($sc_id));
        $s = User::find(Crypt::decrypt($s_id));

        if (is_null($s) || is_null($sc)) {
            return response()->json([
                'message' => 'Wrong URL/URI!',
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($s->sc_id && $s->sc_id !== $sc->id) {
            return response()->json([
                'message' => $s->full_name . ' cannot added to this branch, un-lock him from current GSC!',
            ], Response::HTTP_BAD_REQUEST);
        } else {
            $success_MSG = $s->sc_id === $sc->id ? $s->full_name . ' removed sucessfullt from' . $sc->name . ' committee.' : $s->full_name . ' added sucessfullt to' . $sc->name . ' committee.';

            $s->sc_id = $s->sc_id === $sc->id ? null : $sc->id;
            $isAdded = $s->save();

            return response()->json([
                'message' => $isAdded ? $success_MSG : 'Failed to add supervisor to this GCS, please try again!',
            ], $isAdded ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
        }
    }
}
