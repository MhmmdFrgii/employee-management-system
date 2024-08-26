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
        'fullname',
        'nik',
        'photo',
        'cv',
        'gender',
        'phone',
        'address',
        'hire_date'
    ];

    function user(): mixed
    {
        return $this->belongsTo(User::class);
    }
    function department(): mixed
    {
        return $this->belongsTo(Department::class);
    }
    function position(): mixed
    {
        return $this->belongsTo(Position::class);
    }
}
