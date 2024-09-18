<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function users(): mixed
    {
        return $this->hasMany(User::class);
    }

    public static function company_generate(): string
    {
        $length = 35;
        $unique = false;
        $code = '';

        while (!$unique) {
            $code = strtoupper(Str::random($length));

            $unique = !Company::where('company_code', $code)
                ->where('company_invite', $code)
                ->exists();
        }

        return $code;
    }
}
