<?php

namespace App\Http\Controllers;

use App\Models\APIKEY;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class APIKEYController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $apis = APIKEY::paginate();

        return response()->view('backend.apis.index', [
            'apis' => $apis
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
        return response()->view('backend.apis.store');
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
            'uuid',
            'key',
            'secret',
            'name',
            'status',
            'rate_limit',
        ]), [
            'uuid' => 'required|string|unique:a_p_i_k_e_y_s,id',
            'key' => 'required|string|unique:a_p_i_k_e_y_s,key',
            'secret' => 'required|string',
            'name' => 'required|string|min:3|max:50',
            'status' => 'required|string|in:active,disabled',
            'rate_limit' => 'required|integer|min:0',
        ]);
        //
        if (!$validator->fails()) {
            $api = new APIKEY();
            $api->id = $request->input('uuid');
            $api->key = $request->input('key');
            $api->secret = Hash::make($request->input('secret'));
            $api->name = $request->input('name');
            $api->status = $request->input('status');
            $api->rat_limit = $request->input('rate_limit');
            $api->manager_id = auth()->user()->id;
            $isCreated = $api->save();

            return response()->json([
                'message' => $isCreated ? 'API stored successfull.' : 'Failed to store the API, please try agian!',
            ], $isCreated ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'message' => $validator->getMessageBag()->first(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\APIKEY  $aPIKEY
     * @return \Illuminate\Http\Response
     */
    public function show(APIKEY $aPIKEY)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\APIKEY  $aPIKEY
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $randomString = Str::random(32);
        $secret = sprintf(
            '%08s-%04s-%04s-%04s-%012s',
            substr($randomString, 0, 8),
            substr($randomString, 8, 4),
            substr($randomString, 12, 4),
            substr($randomString, 16, 4),
            substr($randomString, 20)
        );
        $api = APIKEY::findOrFail(Crypt::decrypt($id));
        //
        return response()->view('backend.apis.update', [
            'api' => $api,
            'secret' => $secret,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\APIKEY  $aPIKEY
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $api = APIKEY::findOrFail(Crypt::decrypt($id));
        $validator = Validator($request->only([
            'name',
            'secret',
            'password',
        ]), [
            'name' => 'required|string|min:3|max:50',
            'secret' => 'required|string|min:30',
            'password' => 'required|string',
        ]);
        //
        if (!$validator->fails()) {
            $api->secret = Hash::make($request->input('secret'));
            $api->name = $request->input('name');
            $isUpdated = false;

            if (Hash::check($request->input('password'), auth('manager')->user()->password)) {
                $isUpdated = $api->save();
            } else {
                return response()->json([
                    'message' => 'Wrong password, please try again later!',
                ], Response::HTTP_BAD_REQUEST);
            }

            return response()->json([
                'message' => $isUpdated ? 'API updated successfull.' : 'Failed to update the API, please try agian!',
            ], $isUpdated ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'message' => $validator->getMessageBag()->first(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\APIKEY  $aPIKEY
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($key)
    {
        // return response()->json([
        //     'text' => Crypt::decrypt($id),
        // ], 400);
        // $api = APIKEY::find($id);
        $api = APIKEY::where('key', '=', Crypt::decrypt($key))->first();
        // $api = APIKEY::find($id);
        if (is_null($api)) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Error!',
                'text' => 'Access to unavailable resources!',
            ], 400);
        }
        //
        if ($api->delete()) {
            return response()->json([
                'title' => 'Deleted',
                'text' => 'API deleted successfully.',
                'icon' => 'success',
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'title' => 'Failed!',
                'text' => 'Failed to delete API, please try again!',
                'icon' => 'error',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    // Get apis report
    public function getReport()
    {
        return Excel::download(new APIKEY(), 'APIs.xlsx');
    }

    // Get manager report
    public function getReportSpecificAPI($key)
    {
        // $manager = Manager::findOrFail(Crypt::decrypt($id));
        // $manager = Manager::find(Crypt::decrypt($id));
        return Excel::download(new APIKEY(Crypt::decrypt($key)), 'API.xlsx');
    }
}
