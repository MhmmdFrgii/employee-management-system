<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAssignment extends Model
{
    use HasFactory;

    protected $table = 'project_assignments';
    protected $fillable = ['project_id', 'employee_id', 'role', 'assigned_at'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}