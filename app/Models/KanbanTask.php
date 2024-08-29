<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanTask extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'kanban_boards_id', 'status', 'date', 'color', 'employee_id'];

    public function kanbanboard()
    {
        return $this->belongsTo(KanbanBoard::class, 'kanban_boards_id');
    }

    public function employee()
    {
        return $this->belongsTo(EmployeeDetail::class, 'employee_id');
    }
}
