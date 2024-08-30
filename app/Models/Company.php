<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'contact_email',
        'company_code'
    ];

    public function users(): mixed
    {
        return $this->hasMany(User::class);
    }
}
