<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salary extends Model
{
    use HasFactory;
    protected $table = 'salaries';

    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function employee_detail()
    {
        return $this->belongsTo(EmployeeDetail::class, 'employee_id');
    }

    public function transactions()
    {
        return $this->hasOne(Transaction::class);
    }
}
