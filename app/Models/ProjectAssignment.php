<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAssignment extends Model
{
    use HasFactory;

    protected $table = 'project_assignments';
    protected $fillable = ['project_id', 'employee_id', 'role', 'assigned_at'];

    public function scopeSearch($query, $search)
    {
        return $query->whereHas('project', function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%');
        })->orWhere('employee_id', 'like', '%' . $search . '%')
            ->orWhere('role', 'like', '%' . $search . '%')
            ->orWhere('assigned_at', 'like', '%' . $search . '%');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function employe()
    {
        return $this->belongsTo(EmployeeDetail::class, 'employee_id');
    }
}