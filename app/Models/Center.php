<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Center extends Model implements FromCollection, WithHeadings, WithStyles
{
    use HasFactory, SoftDeletes;

    // Attributes
    const STATUS = ['active', 'pending', 'inactive'];
    const POSITION = 'center';
    protected $columns = [
        'id',
        'name',
        'region',
        'status',
        'created_at',
        'updated_at',
    ];
    protected $center_id;

    public function __construct($center_id = 0)
    {
        $this->center_id = $center_id;
    }

    public function collection()
    {
        if (!$this->center_id) {
            return Center::select($this->columns)->get();
        } else {
            return Center::select($this->columns)
                ->where('id', '=', $this->center_id)
                ->get();
        }
    }

    public function headings(): array
    {
        return $this->columns;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:N1')->getFill()->setFillType('solid')->getStartColor()->setARGB('FFC0C0C0');
    }

    // Get Attributes
    public function getCenterStatusClassAttribute()
    {
        $class = 'label font-weight-bold label-lg  label-light-success label-inline';
        if ($this->status === 'inactive') {
            $class = 'label font-weight-bold label-lg  label-light-danger label-inline';
        } else if ($this->status === 'pending') {
            $class = 'label font-weight-bold label-lg  label-light-info label-inline';
        }
        return $class;
    }

    public function getCenterDeletionAttribute()
    {
        return $this->deleted_at == null ? 'F' : 'T';
    }

    public function getCenterDeletionClassAttribute()
    {
        return $this->deleted_at == null ? 'success' : 'danger';
    }

    // Scopes
    public function scopeActive ($query) {
        return $query->where('status', 'active');
    }

    // Relations
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function groups()
    {
        return $this->hasMany(Group::class, 'center_id', 'id');
    }
}
