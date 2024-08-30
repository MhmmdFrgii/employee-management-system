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

    public function kanbantasks()
    {
        return $this->hasMany(KanbanTask::class, 'kanban_boards_id');
    }
}
