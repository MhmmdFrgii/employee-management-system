<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'status'
    ];

    public function employee_detail()
    {
        return $this->belongsTo(EmployeeDetail::class, 'employee_id');
    }
}
