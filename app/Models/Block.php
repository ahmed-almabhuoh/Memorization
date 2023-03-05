<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    // Attributes
    const POSITIONS = [
        'manager', 'admin', 'supervisor', 'keeper', 'parent', 'student',
    ];
    const STATUS = [
        'active', 'disable'
    ];

    // Attributes
    public function getBlockStatusClassAttribute()
    {
        $class = 'label font-weight-bold label-lg  label-light-success label-inline';
        if ($this->status === 'active') {
            $class = 'label font-weight-bold label-lg  label-light-danger label-inline';
        } else if ($this->status === 'inactive') {
            $class = 'label font-weight-bold label-lg  label-light-info label-inline';
        }
        return $class;
    }

    // Scopes
    public function scopeAdminActiveBlocks($query)
    {
        return $query->adminPosition()->where('status', '=', 'active');
    }

    public function scopeAdminDisabledBlocks($query)
    {
        return $query->adminPosition()->where('status', '=', 'disabled');
    }

    public function scopeAdminPosition($query)
    {
        return $query->where('position', '=', 'admin');
    }


    // Relations
    public function manager()
    {
        return $this->belongsTo(User::class, 'blocked_id', 'id')->where('position', 'manager');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'blocked_id', 'id')->where('position', 'admin');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'blocked_id', 'id')->where('position', 'supervisor');
    }

    public function keeper()
    {
        return $this->belongsTo(User::class, 'blocked_id', 'id')->where('position', 'keeper');
    }

    public function parents()
    {
        return $this->belongsTo(User::class, 'blocked_id', 'id')->where('position', 'parent');
    }

    public function students()
    {
        return $this->belongsTo(User::class, 'blocked_id', 'id')->where('position', 'student');
    }
}
