<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'position_id',
        'name',
        'photo',
        'email',
        'gender',
        'phone',
        'address',
        'hire_date'
    ];

    function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    function department()
    {
        return $this->belongsTo(Department::class);
    }
    function position()
    {
        return $this->belongsTo(Position::class);
    }
    function attendances(): mixed
    {
        return $this->hasMany(Attendance::class, 'employee_id');
    }
    public function salary()
    {
        return $this->hasOne(Salary::class);
    }
}