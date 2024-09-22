<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'departments';

    protected $fillable = [
        'name',
        'description',
        'company_id'
    ];

    function employee_details(): mixed
    {
        return $this->hasMany(EmployeeDetail::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
    public function department()
{
    return $this->belongsTo(Department::class);
}
}
