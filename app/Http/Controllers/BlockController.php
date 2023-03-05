<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\User;
use Dotenv\Validator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class BlockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $blocked_id, $position)
    {
        $user = User::findOrFail($blocked_id);
        $validator = Validator($request->only([
            'block_description',
            'from_date',
            'to_date',
        ]), [
            'block_description' => 'nullable|min:5|max:150',
            'from_date' => 'nullable',
            'to_date' => 'nullable',
        ]);
        //
        if (!$validator->fails()) {

            $isCreated = false;
            DB::beginTransaction();
            try {
                $block = new Block();
                $block->blocked_id = $user->id;
                $block->description = $request->post('description');
                $block->from = $request->post('from_date');
                $block->to = $request->post('to_date');
                $block->position = $user->position;
                $isCreated = $block->save();

                $user->status = 'blocked';
                $user->save();

                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();

                return response()->json([
                    'message' => 'Un-expected error!'
                ], Response::HTTP_BAD_REQUEST);
            }


            return response()->json([
                'message' => $isCreated ? 'User blocked successfully' : 'Failed to block user, please try again!',
            ], $isCreated ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'message' => $validator->getMessageBag()->first(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($blocked_id, $position = 'manager')
    {
        $blocked_id = Crypt::decrypt($blocked_id);
        $blocks = Block::where([
            ['position', '=', $position],
            ['blocked_id', '=', $blocked_id],
        ])->paginate();
        //
        return response()->view('backend.blocks.index', [
            'position' => $position,
            'blocks' => $blocks,
            'user' => User::findOrFail($blocked_id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Block $block)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Block $block)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Block $block)
    {
        //
    }
}
