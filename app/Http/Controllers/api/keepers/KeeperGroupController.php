<?php

namespace App\Http\Controllers\api\keepers;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KeeperGroupController extends Controller
{
    //

    /*
     * Get Keeper Group
     * */
    public function getGroups () {
        return response()->json([
            'groups' => auth()->user()->group()->paginate(),
        ], Response::HTTP_OK);
    }

    public function getGroupStudents($group_id = null) {
        if ($group_id) {
            $group = Group::whereHas('keeper', function ($query) {
                $query->where('id', auth()->user()->id);
            })->where('id', $group_id)->first();

            return \response()->json([
                'group' => $group,
                'students' => $group->students,
            ], Response::HTTP_OK);
        }else {
            $groups = Group::whereHas('keeper', function ($query) {
                $query->where('id', auth()->user()->id);
            })->get();

            return \response()->json([
                'groups' => $groups,
            ], Response::HTTP_OK);
        }
    }
}
