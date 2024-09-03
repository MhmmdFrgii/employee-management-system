<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $table = 'projects';
    protected $guarded = ['id'];

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
                ->orWhere('start_date', 'like', '%' . $search . '%')
                ->orWhere('end_date', 'like', '%' . $search . '%')
                ->orWhere('status', 'like', '%' . $search . '%');
        });
    }

    public function kanban_board()
    {
        return $this->hasOne(KanbanBoard::class);
    }

    // public function employee_details(): mixed
    // {
    //     return $this->belongsToMany(EmployeeDetail::class, 'project_assignments', 'project_id', 'employee_id');
    // }
    public function employee_details()
    {
        return $this->belongsToMany(EmployeeDetail::class, 'project_assignments', 'project_id', 'employee_id');
    }

    // public function department()
    // {
    //     return $this->belongsTo(Department::class);
    // }

    public function departments()
    {
        return $this->hasManyThrough(Department::class, EmployeeDetail::class, 'project_id', 'id', 'id', 'department_id');
    }
}