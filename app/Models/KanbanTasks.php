<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanTasks extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'kanban_boards_id', 'status', 'date'];

    public function kanbanboard()
    {
        return $this->belongsTo(KanbanBoard::class, 'kanban_boards_id');
    }

}
