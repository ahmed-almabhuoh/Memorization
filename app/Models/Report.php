<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    const STATUS = ['pending', 'rejected', 'approved'];
    const TYPE = ['keeps', 'tests'];


    public function user () {
        return $this->belongsTo(User::class, 'keeper_id', 'id');
    }
}
