<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class User extends Authenticatable implements FromCollection, WithHeadings, WithStyles
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    // Attributes
    protected $columns = [
        'id',
        'fname',
        'sname',
        'tname',
        'lname',
        'email',
        'phone',
        'identity_no',
        'gender',
        'status',
        'local_region',
        'description',
        'updated_at',
        'created_at',
        'position',
    ];
    protected $user_id;
    const POSITIONS = [
        'manager', 'admin', 'supervisor', 'keeper', 'parent', 'student'
    ];
    protected $position = '';
    const GENDER = ['male', 'female'];
    const STATUS = ['active', 'draft', 'blocked'];
    protected $fillable = [
        'id',
        'fname',
        'sname',
        'tname',
        'lname',
        'email',
        'phone',
        'identity_no',
        'gender',
        'status',
        'local_region',
        'description',
        'password',
    ];
    protected $hidden = ['password'];

    public function __construct($user_id = 0, $position = '')
    {
        $this->user_id = $user_id;
    }

    public function collection()
    {
        if (!$this->user_id || $this->position === '') {
            return User::select($this->columns)->get();
        } else {
            return User::select($this->columns)
                ->where('position', '=', $this->position)
                ->where('id', '=', $this->user_id)
                ->get();
        }
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:N1')->getFill()->setFillType('solid')->getStartColor()->setARGB('FFC0C0C0');
    }

    public function headings(): array
    {
        return $this->columns;
    }



    // Get - Attributes
    public function getFullNameAttribute()
    {
        return $this->fname . ' ' . $this->sname . ' ' . $this->tname . ' ' . $this->lname;
    }

    public function getUserStatusClassAttribute()
    {
        $class = 'label font-weight-bold label-lg  label-light-success label-inline';
        if ($this->status === 'blocked') {
            $class = 'label font-weight-bold label-lg  label-light-danger label-inline';
        } else if ($this->status === 'draft') {
            $class = 'label font-weight-bold label-lg  label-light-info label-inline';
        }
        return $class;
    }

    public function getUserGenderClassAttribute()
    {
        return $this->status === 'male' ? 'font-weight-bold text-primary' : 'font-weight-bold text-primary';
    }

    public function getLastBlockAttribute()
    {
        return $this->blocks->first();
    }

    public function getUserDeletionAttribute()
    {
        return $this->deleted_at == null ? 'F' : 'T';
    }

    public function getUserDeletionClassAttribute()
    {
        return $this->deleted_at == null ? 'success' : 'danger';
    }



    // Scopes
    public function scopeAdmin ($query) {
        return $query->where('position', 'admin');
    }

    public function scopeKeeper ($query) {
        return $query->where('position', 'keeper');
    }

    public function scopeActiveKeeper ($query) {
        return $query->keeper()->where('status', 'active');
    }

    public function scopeKeeperWithoutGroup ($query) {
        return $query->activeKeeper()->whereDoesntHave('group');
    }

    public function scopeKeeperOwnWithoutGroup ($query) {
        return $query->activeKeeper()->whereDoesntHave('group', function ($query) {
            return $query->where('id', '=', $this->id);
        });
    }

    public function scopeStudents ($query) {
        return $query->where('position', '=', 'student');
    }


    // Relations
    public function blocks()
    {
        return $this->hasMany(Block::class, 'blocked_id', 'id')->orderBy('created_at', 'DESC');
    }

    public function group()
    {
        return $this->hasOne(Group::class, 'keeper_id', 'id');
    }

    public function apis()
    {
        return $this->hasMany(APIKEY::class, 'manager_id', 'id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_student', 'group_id', 'student_id');
    }

    public function sc()
    {
        return $this->belongsTo(SupervisionCommittee::class, 'sc_id', 'id');
    }

    public function branch()
    {
        return $this->hasOne(Branch::class, 'supervisor_id', 'id');
    }

    public function keeps () {
        return $this->hasMany(Keeps::class, 'student_id', 'id');
    }
}
