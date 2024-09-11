<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanBoard extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'project_id', 'description'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    public function employee_detail()
    {
        return $this->belongsTo(EmployeeDetail::class, 'employee_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kanbantasks()
    {
        return $this->hasMany(KanbanTask::class, 'kanban_boards_id');
    }
}
