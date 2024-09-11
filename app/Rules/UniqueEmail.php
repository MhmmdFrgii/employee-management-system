<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueEmail implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $userExists = DB::table('users')->where('email', $value)->exists();

        $employeeExists = DB::table('employee_details')->where('email', $value)->exists();

        if ($userExists || $employeeExists) {
            $fail('Email Sudah digunakan.');
        }
    }
}
