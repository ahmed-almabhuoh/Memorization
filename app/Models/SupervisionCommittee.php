<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SupervisionCommittee extends Model implements FromCollection, WithHeadings, WithStyles
{
    use HasFactory, SoftDeletes;

    // Attributes
    const STATUS = ['active', 'pending', 'inactive'];
    const POSITION = 'supervision_committee';
    protected $columns = [
        'id',
        'name',
        'region',
        'status',
        'created_at',
        'updated_at',
    ];
    protected $supervision_committee_id;
    const  TYPES = [
        'PaQ', 'memorization'
    ];

    public function __construct($supervision_committee_id = 0)
    {
        $this->supervision_committee_id = $supervision_committee_id;
    }

    public function collection()
    {
        if (!$this->supervision_committee_id) {
            return SupervisionCommittee::select($this->columns)->get();
        } else {
            return SupervisionCommittee::select($this->columns)
                ->where('id', '=', $this->supervision_committee_id)
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
    public function getSCStatusClassAttribute()
    {
        $class = 'label font-weight-bold label-lg  label-light-success label-inline';
        if ($this->status === 'inactive') {
            $class = 'label font-weight-bold label-lg  label-light-danger label-inline';
        } else if ($this->status === 'pending') {
            $class = 'label font-weight-bold label-lg  label-light-info label-inline';
        }
        return $class;
    }

    public function getSCDeletionAttribute()
    {
        return $this->deleted_at == null ? 'F' : 'T';
    }

    public function getSCDeletionClassAttribute()
    {
        return $this->deleted_at == null ? 'success' : 'danger';
    }

    // Relations
    public function supervisors()
    {
        return $this->hasMany(User::class, 'sc_id', 'id');
    }
}
