<?php

namespace App\Listeners;

use App\Models\Block;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateBlockUserListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        //
        if ($event->request->input('is_blocked')) {
            $block = new Block();
            $block->description = $event->request->input('block_description');
            $block->position = $event->request-input('position');
            $block->blocked_id  = $event->admin->id;
            $block->from = $event->request->input('from_date');
            $block->to = $event->request->input('to_date');
            $block->save();
        } else {
            return;
        }
    }
}
