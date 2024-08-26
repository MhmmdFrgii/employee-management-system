<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $table = 'projects';
    protected $fillable = ['name', 'description', 'start_date', 'end_date', 'status'];

    public function scopeSearch($query, $search)
    {
    
        return $query->where('name', 'like', '%' . $search . '%')
        ->orWhere('description', 'like', '%' . $search . '%')
        ->orWhere('start_date', 'like', '%' . $search . '%')
        ->orWhere('end_date', 'like', '%' . $search . '%')
        ->orWhere('status', 'like', '%' . $search . '%')
        ;
    }

    public function project_assignments()
    {
        return $this->hasMany(ProjectAssignment::class);
    }

    public function kanbanboard()
    {
        return $this->hasMany(KanbanBoard::class);
    }

}
