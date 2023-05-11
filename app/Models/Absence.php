<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    use HasFactory;

    public function keeper () {
        return $this->belongsTo(User::class, 'user_id', 'id')->where('position', 'keeper');
    }

    public function student () {
        return $this->belongsTo(User::class, 'user_id', 'id')->where('position', 'student');
    }
}
