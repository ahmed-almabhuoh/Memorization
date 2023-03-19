<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    const TYPE = ['single', 'group'];

    /*
     * Scopes
     * */
    public function scopeOwn ($query)
    {
        $position = auth()->user()->position;
        if ($position == 'keeper') {
            return $query->where('keeper', function ($query) {
                $query->where([
                    ['keeper_id', '=', auth()->user()->id],
                    ['position', '=', 'keeper'],
                ]);
            });
        } else if ($position == 'student') {
            return $query->where('student', function ($query) {
                $query->where([
                    ['student_id', '=', auth()->user()->id],
                    ['position', '=', 'student'],
                ]);
            });
        }
    }

//    public function scopeOwnKeeper ($query) {
//        return $query->where('keeper', function ($query) {
//            $query->where('keeper_id', '=', auth()->user()->id);
//        });
//    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function keeper()
    {
        return $this->belongsTo(User::class, 'keeper_id', 'id');
    }

    public function sc()
    {
        return $this->belongsTo(User::class, 'sc_id', 'id');
    }
}
