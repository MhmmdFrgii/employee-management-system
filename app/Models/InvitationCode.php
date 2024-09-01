<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InvitationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'company_id',
        'used_by',
        'status'
    ];

    public static function invitation_generate(): string
    {
        $length = 75;
        $unique = false;
        $code = '';

        while (!$unique) {
            $code = strtoupper(Str::random($length));

            $unique = !InvitationCode::where('code', $code)->exists();
        }

        return $code;
    }
}
