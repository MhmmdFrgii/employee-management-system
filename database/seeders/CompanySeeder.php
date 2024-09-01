<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'name' => 'PT. Modernize',
            'address' => 'Jl. Manggis',
            'contact_email' => 'company@email.com',
            'company_code' => Company::company_generate()
        ]);
    }
}
