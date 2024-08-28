<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salarie extends Model
{
    use HasFactory;
    protected $table = 'salaries';

    protected $fillable = [
        'employee_id',
        'amount',
        'payment_date',
    ];

    public function employeeDetails()
    {
        return $this->belongsTo(EmployeeDetail::class);
    }

}
