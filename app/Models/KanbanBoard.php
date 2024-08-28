<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanBoard extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'project_id', 'description'];

    public function projects()
    {
        return $this->belongsTo(Project::class, 'projects_id');
    }

    public function kanbantasks()
    {
        return $this->hasMany(KanbanTasks::class);
    }
}
