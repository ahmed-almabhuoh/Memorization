<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class APIKEY extends Model implements FromCollection, WithHeadings, WithStyles
{
    use HasFactory, SoftDeletes;


    protected $table = 'a_p_i_k_e_y_s';

    // Attributes
    protected $columns = [
        'id',
        'key',
        'name',
        'status',
        'rat_limit',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    const POSITION = 'api';
    protected $api_key;
    const STATUS = ['active', 'disabled'];
    protected $fillable = [
        'id',
        'key',
        'name',
        'status',
        'rat_limit',
    ];
    protected $hidden = ['secret'];

    public function __construct($api_key = 0)
    {
        $this->api_key = $api_key;
    }

    public function collection()
    {
        if (!$this->api_key) {
            return APIKEY::select($this->columns)->get();
        } else {
            return APIKEY::select($this->columns)
                ->where('key', '=', $this->api_key)
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



    // Relations
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id', 'id');
    }

    // Attributes
    public function getApiStatusClassAttribute()
    {
        $class = 'label font-weight-bold label-lg  label-light-success label-inline';
        if ($this->status === 'disabled') {
            $class = 'label font-weight-bold label-lg  label-light-danger label-inline';
        }
        return $class;
    }

    public function getApiRatLimitAttribute()
    {
        return $this->rat_limit == 0 ? 'Open' : $this->rat_limit;
    }

    public function getApiDeletionAttribute()
    {
        return $this->deleted_at == null ? 'F' : 'T';
    }

    public function getApiDeletionClassAttribute()
    {
        return $this->deleted_at == null ? 'success' : 'danger';
    }
}
